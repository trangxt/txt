<?php
/*------------------------------------------------------------------------
# $JA#PRODUCT_NAME$ - Version $JA#VERSION$ - Licence Owner $JA#OWNER$
# ------------------------------------------------------------------------
# Copyright (C) 2004-2008 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/

define ('JA_TOOL_COLOR', 'ja_color');
define ('JA_TOOL_SCREEN', 'ja_screen');
define ('JA_TOOL_FONT', 'ja_font');
define ('JA_TOOL_LAYOUT', 'ja_layout');
define ('JA_TOOL_BODY', 'ja_body');
define ('JA_TOOL_MENU', 'ja_menu');
define ('JA_TOOL_USER', 'usertool');

class JA_Tools {	
	var $_params_cookie = array(); //Params will store in cookie for user select. Default: store all params
	var $_params = null; //Params will store in cookie for user select. Default: store all params
	var $template = 'ja_labra';
	//This default value could override by setting with function setScreenSizes() and setColorThemes()
	var $_ja_layouts = null;
	var $_ja_body_themes = null;
	var $_ja_screen_sizes = null;
	var $_ja_color_themes = null;

	function JA_Tools ($template, $_param, $_params_cookie=null) {
		$this->template = $template;
		$this->_params = $_param;
		if(!$_params_cookie) {
			$_params_cookie = array();
		}
		
		if ($this->getParam('usertool_screen') && !in_array(JA_TOOL_SCREEN, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_SCREEN;
		}
		if ($this->getParam('usertool_font') && !in_array(JA_TOOL_FONT, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_FONT;
		}
		if ($this->getParam('usertool_color') && !in_array(JA_TOOL_COLOR, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_COLOR;
		}
		
		if ($this->getParam('usertool_layout') && !in_array(JA_TOOL_LAYOUT, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_LAYOUT;
		}
		if ($this->getParam('usertool_body') && !in_array(JA_TOOL_BODY, $_params_cookie)) {
			$_params_cookie[]=JA_TOOL_BODY;
		}
		if($_params_cookie){
			foreach ($_params_cookie as $k) {
				$this->_params_cookie[$k] = $this->_params->get($k);
			}
		}
		$this->getUserSetting();
	}

	function getUserSetting(){
		$exp = time() + 60*60*24*355;
		if (isset($_COOKIE[$this->template.'_tpl']) && $_COOKIE[$this->template.'_tpl'] == $this->template){
			foreach($this->_params_cookie as $k=>$v) {
				$kc = $this->template."_".$k;
				if (isset($_GET[$k])){
					$v = $_GET[$k];
					setcookie ($kc, $v, $exp, '/');
				}else{
					if (isset($_COOKIE[$kc])){
						$v = $_COOKIE[$kc];
					}
				}
				$this->setParam($k, $v);
			}

		}else{
			@setcookie ($this->template.'_tpl', $this->template, $exp, '/');
		}
		return $this;
	}

	function getParam ($param, $default='') {
		if (isset($this->_params_cookie[$param])) {
			return $this->_params_cookie[$param];
		}
		return $this->_params->get($param, $default);
	}

	function setParam ($param, $value) {
		$this->_params_cookie[$param] = $value;
	}

	function genToolMenu($ja_tools, $imgext = 'gif'){
		if($ja_tools & 1){//show screen tools
			?>
			<ul class="ja-usertools-screen">
			<?php					
			foreach ($this->_ja_screen_sizes as $ja_screen_size) {
				echo "
				<li><img style=\"cursor: pointer;\" alt=\"$ja_screen_size\" src=\"".$this->skinurl()."/images/".strtolower($ja_screen_size).( ($this->getParam(JA_TOOL_SCREEN)==$ja_screen_size) ? "-hilite" : "" ).".".$imgext."\" title=\"".$ja_screen_size."\" alt=\"".$ja_screen_size."\" id=\"ja-tool-".$ja_screen_size."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_SCREEN."','$ja_screen_size');return false;\" /></li>
				";
			}
			?>
			</ul>
		<?php 
		} 
		
		if ($ja_tools & 2){//show font tools
  		?>
	  		<ul class="ja-usertools-font">
	  		<li><img style="cursor: pointer;" title="Increase font size" src="<?php echo $this->skinurl();?>/images/user-increase.<?php echo $imgext;?>" alt="Increase font size" id="ja-tool-increase" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>','inc'); return false;" /></li>
	  		<li><img style="cursor: pointer;" title="Default font size" src="<?php echo $this->skinurl();?>/images/user-reset.<?php echo $imgext;?>" alt="Default font size" id="ja-tool-reset" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>',<?php echo $this->_params->get(JA_TOOL_FONT);?>); return false;" /></li>
	  		<li><img style="cursor: pointer;" title="Decrease font size" src="<?php echo $this->skinurl();?>/images/user-decrease.<?php echo $imgext;?>" alt="Decrease font size" id="ja-tool-decrease" onclick="switchFontSize('<?php echo $this->template."_".JA_TOOL_FONT;?>','dec'); return false;" /></li>
	  		</ul>
	  		<script type="text/javascript">var CurrentFontSize=parseInt('<?php echo $this->getParam(JA_TOOL_FONT);?>');</script>	  			  	
		<?php } ?>
		
    	<?php 
		if ($ja_tools & 4){//show color tools
			?>
			<ul class="ja-usertools-color">
			<?php			
			foreach ($this->_ja_color_themes as $ja_color_theme) {
				echo "
				<li><img style=\"cursor: pointer;\" src=\"".$this->skinurl()."/images/".strtolower($ja_color_theme).( ($this->getParam(JA_TOOL_COLOR)==$ja_color_theme) ? "-hilite" : "" ).".".$imgext."\" title=\"".$ja_color_theme." color\" alt=\"".$ja_color_theme." color\" id=\"ja-tool-".$ja_color_theme."color\" onclick=\"switchTool('".$this->template."_".JA_TOOL_COLOR."','$ja_color_theme');return false;\" /></li>
				";
			} ?>
			</ul>
		<?php
		}
		
		if ($ja_tools & 8){//show LAYOUT tools
			?>
			<ul class="ja-usertools-layout">
			<?php
				foreach ($this->_ja_layouts as $ja_layout) {
					echo "
					<li><img style=\"cursor: pointer;\" src=\"".$this->skinurl()."/images/".strtolower($ja_layout).( ($this->getParam(JA_TOOL_LAYOUT)==$ja_layout) ? "-hilite" : "" ).".".$imgext."\" title=\"$ja_layout\" alt=\"$ja_layout\" id=\"ja-tool-".$ja_layout."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_LAYOUT."','$ja_layout');return false;\" /></li>
					";
				} 
			?>
			</ul>
		<?php
		}

		if ($ja_tools & 16){//show LAYOUT tools
			?>
			<ul class="ja-usertools-body">
			<?php
				foreach ($this->_ja_body_themes as $ja_body_theme) {
					echo "
					<li><img style=\"cursor: pointer;\" src=\"".$this->skinurl()."/images/".strtolower($ja_body_theme).( ($this->getParam(JA_TOOL_BODY)==$ja_body_theme) ? "-hilite" : "" ).".".$imgext."\" title=\"$ja_body_theme\" alt=\"$ja_body_theme\" id=\"ja-tool-".$ja_body_theme."\" onclick=\"switchTool('".$this->template."_".JA_TOOL_BODY."','$ja_body_theme');return false;\" /></li>
					";
				} 
			?>
			</ul>
		<?php
		}
  	
		if ($ja_tools & 32){//show font tools
  		?>
	  		<ul class="ja-usertools-expand">
	  		<li id="ja-mainbody-resize"></li>
	  		</ul>
	  		<?php
	    }
    
		if ($ja_tools & 64){//show font tools
	  		?>
	  		<div class="ja-usertools-modfunc">
	  		<a href="" title="Reset Module Status" onclick="switchTool('ja-ordercolumn','-'); return false;" >Reset Module Status</a>
	  		</div>
	  		<?php
	    }
	}

	function setLayouts ($_array_layouts) {
		$this->_ja_layouts = $_array_layouts;
	}
	
	function setBodyThemes ($_array_body_themes){
		$this->_ja_body_themes = $_array_body_themes ;
	}
	
	function setScreenSizes ($_array_screen_sizes) {
		$this->_ja_screen_sizes = $_array_screen_sizes;
	}

	function setColorThemes ($_array_color_themes) {
		$this->_ja_color_themes = $_array_color_themes;
	}
	
	function isIE6 () {
		$msie='/msie\s(5\.[5-9]|[6]\.[0-9]*).*(win)/i';
		return isset($_SERVER['HTTP_USER_AGENT']) &&
			preg_match($msie,$_SERVER['HTTP_USER_AGENT']) &&
			!preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
	}
	
	function isOP () {
		return isset($_SERVER['HTTP_USER_AGENT']) &&
			preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']);
	}

	function baseurl(){
		return $this->getBaseURL();
	}
	
	function getBaseURL() {
		static $_baseURL = '';
		if (!$_baseURL) {
			// Determine if the request was over SSL (HTTPS)
			if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
				$https = 's://';
			} else {
				$https = '://';
			}

			/*
			 * Since we are assigning the URI from the server variables, we first need
			 * to determine if we are running on apache or IIS.  If PHP_SELF and REQUEST_URI
			 * are present, we will assume we are running on apache.
			 */
			if (!empty ($_SERVER['PHP_SELF']) && !empty ($_SERVER['REQUEST_URI'])) {

				/*
				 * To build the entire URI we need to prepend the protocol, and the http host
				 * to the URI string.
				 */
				$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

			/*
			 * Since we do not have REQUEST_URI to work with, we will assume we are
			 * running on IIS and will therefore need to work some magic with the SCRIPT_NAME and
			 * QUERY_STRING environment variables.
			 */
			}
			 else
			{
				// IIS uses the SCRIPT_NAME variable instead of a REQUEST_URI variable... thanks, MS
				$theURI = 'http' . $https . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

				// If the query string exists append it to the URI string
				if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
					$theURI .= '?' . $_SERVER['QUERY_STRING'];
				}
			}

			// Now we need to clean what we got since we can't trust the server var
			$theURI = urldecode($theURI);
			$theURI = str_replace('"', '&quot;',$theURI);
			$theURI = str_replace('<', '&lt;',$theURI);
			$theURI = str_replace('>', '&gt;',$theURI);
			$theURI = preg_replace('/eval\((.*)\)/', '', $theURI);
			$theURI = preg_replace('/[\\\"\\\'][\\s]*javascript:(.*)[\\\"\\\']/', '""', $theURI);	
			
			//Parse theURL
			$_parts = $this->_parseURL ($theURI);
			$_baseURL = '';
			$_baseURL .= (!empty($_parts['scheme']) ? $_parts['scheme'].'://' : '');
			$_baseURL .= (!empty($_parts['host']) ? $_parts['host'] : '');
			$_baseURL .= (!empty($_parts['port']) && $_parts['port']!=80 ? ':'.$_parts['port'] : '');

			if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
				//Apache CGI
				$_path =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			} else {
				//Others
				$_path =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
			}

			$_baseURL .= $_path;
		}
		return $_baseURL;
	}

	function _parseURL($uri)
	{
		$parts = array();
		if (version_compare( phpversion(), '4.4' ) < 0)
		{
			$regex = "<^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\\?([^#]*))?(#(.*))?>";
			$matches = array();
			preg_match($regex, $uri, $matches, PREG_OFFSET_CAPTURE);

			$authority = @$matches[4][0];
			if (strpos($authority, '@') !== false) {
				$authority = explode('@', $authority);
				@list($parts['user'], $parts['pass']) = explode(':', $authority[0]);
				$authority = $authority[1];
			}

			if (strpos($authority, ':') !== false) {
				$authority = explode(':', $authority);
				$parts['host'] = $authority[0];
				$parts['port'] = $authority[1];
			} else {
				$parts['host'] = $authority;
			}

			$parts['scheme'] = @$matches[2][0];
			$parts['path'] = @$matches[5][0];
			$parts['query'] = @$matches[7][0];
			$parts['fragment'] = @$matches[9][0];
		}
		else
		{
			$parts = @parse_url($uri);
		}
		return $parts;
	}
	
	function templateurl(){
		return $this->getBaseURL()."/app/design/frontend/default/".$this->template;
	}

	function skinurl(){
		return $this->getBaseURL()."/skin/frontend/default/".$this->template;
	}

	function getRandomImage ($img_folder) {
		$imglist=array();

		mt_srand((double)microtime()*1000);

		//use the directory class
		$imgs = dir($img_folder);

		//read all files from the  directory, checks if are images and ads them to a list (see below how to display flash banners)
		while ($file = $imgs->read()) {
			if (eregi("gif", $file) || eregi("jpg", $file) || eregi("png", $file))
				$imglist[] = $file;
		}
		closedir($imgs->handle);

		if(!count($imglist)) return '';

		//generate a random number between 0 and the number of images
		$random = mt_rand(0, count($imglist)-1);
		$image = $imglist[$random];

		return $image;
	}	
	
	function isHomepage () {
		if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
			//Apache CGI
			$_path =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		} else {
			//Others
			$_path =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
		}
		$uri = $_SERVER['REQUEST_URI'];
		if ($uri && $_path && strpos ($uri, $_path) === 0) {
			$uri = substr($uri, strlen ($_path));
		}
		$uri = strtolower($uri);
		if (in_array($uri, array('', '/', '/index.php', '/home', '/home/', '/default', '/default/', '/default/home', '/default/home/'))) return $uri;
		if (strpos($uri, '/home-')===0) return $uri;
		return FALSE;		
	}
	
	function checkHomepage() {
		if (($uri = $this->isHomepage())===FALSE) return;
		
		$ja_color = $this->getParam ('ja_color');
		if (strpos($uri, '/home-')!==FALSE) {
			$arr = split('/home-', $uri, 2);
			$color = count($arr)>1?$arr[1]:'';
			if ((!$color && $ja_color == 'default')||($color == $ja_color)) return;
			
			//Redirect
			/*$page = '/home';
			if ($ja_color != '' && $ja_color != 'default') $page .= '-'.$ja_color;
			header ('Location: '.$this->baseurl().$page);
			exit;*/
		}
		//Redirect if default homepage and other color
		/*if ($ja_color != '' && $ja_color != 'default') {
			header ('Location: '.$this->baseurl().'/home-'.$ja_color);
			exit;
		}*/
	}
}

class JParameter {
	var $_params;
	function JParameter () {
		$this->_params = array();
	}
	function get ($key, $default = '') {
		return isset($this->_params[$key])?$this->_params[$key]:$default;
	}
	function set ($key, $value = '') {
		$this->_params[$key] = $value;
	}
}
?>
