<?php
/*------------------------------------------------------------------------
# $JA#PRODUCT_NAME$ - Version $JA#VERSION$ - Licence Owner $JA#OWNER$
# ------------------------------------------------------------------------
# Copyright (C) 2004-2009 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: J.O.O.M Solutions Co., Ltd
# Websites: http://www.joomlart.com - http://www.joomlancers.com
# This file may not be redistributed in whole or significant part.
-------------------------------------------------------------------------*/ 

class JoomlArt_JmRSS_Block_List extends Mage_Core_Block_Template
{
	var $_cachelife='';
	var $_config = array();	
	var $_show = 0;
	var $_rss_content = array();
	
	public function __construct($attributes = array()){
		parent::__construct();
		
		$helper =  Mage::helper('joomlart_jmrss/data');
		$this->_config = 	 $helper->init($attributes);
		$this->_show = $this->_config['show'];
        if(!$this->_show) return ;		   
	}	
				
	function _toHtml() {	
		if(!$this->_show) return ;
		$this->init_rss();
		
		if(!isset($this->_config['template']) || $this->_config['template']==''){
			$this->_config['template'] = 'joomlart/jmrss/list.phtml';
		}
		
		$this->setTemplate($this->_config['template']);	
		
        return parent::_toHtml();		
	}		

	function init_rss(){
		require_once(dirname(__FILE__).DS.'lastRSS.php');
		// Create lastRSS object
		$rss = new lastRSS; 
		if(isset($this->_config['cachemode']) && $this->_config['cachemode']){
			$rss->cache_dir = './var/cache';
			$rss->cache_time = $this->_config['timecache'];
		}
		else{
			$rss->cache_dir = '';
		}
		
		$rss_content = $rss->get($this->_config['seturl']);
		$this->_rss_content = $rss_content;
		$k = 0;
		$obj_smart_trim = new SmartTrim();
		$this->_rss_content['new_items'] = array();
		$this->_config['aquanlity'] = (int)$this->_config['aquanlity'];
		if ($rss_content) {
			foreach($rss_content['items'] as $item) {	
				if($k<$this->_config['aquanlity']){	
					if(array_key_exists("description", $item)){
                        $item['description'] = html_entity_decode($item['description'], ENT_COMPAT, 'UTF-8');
					    if (!$this->_config['support_html']) {
						    $item['description'] = strip_tags($item['description']);
					    }
					    if($this->_config['maxchars']){
						    if (function_exists('mb_substr')) {
							    $item['description'] = $obj_smart_trim->mb_trim($item['description'], 0, $this->_config['maxchars'], 'utf-8');
						    } else {
							    $item['description'] = $obj_smart_trim->trim($item['description'], 0, $this->_config['maxchars']);
						    }
					    }
					    else $item['description'] = '';
                    }    
                        
					$this->_rss_content['new_items'][] = $item;
					$k++;
				}
				else break;
			}
		}
		
	}
	
	function set($params){
		$params = preg_split ("/\n/", $params);
		foreach ($params as $param){	
			$param = trim($param);
			if (!$param) continue;
			$param = preg_split ("/=/", $param);
			if (count($param) == 2 && strlen(trim($param[1])) > 0)
				$this->_config[trim($param[0])] =  trim($param[1]);
		}
		
	}
}

class SmartTrim {    
    /*
      $hiddenClasses: Class that have property display: none or invisible.
    */
    function mb_trim($strin, $pos=0, $len=10000, $hiddenClasses = '', $encoding='utf-8' ) {
			mb_internal_encoding($encoding);
      $strout = trim($strin);
      
      $pattern = '/(<[^>]*>)/';
      $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
      $left = $pos;
      $length = $len;
      $strout = '';
      for($i=0;$i<count($arr);$i++) {
        $arr[$i] = trim($arr[$i]);
        if ($arr[$i] == '') continue;
        if ($i % 2 == 0) {
          if($left > 0) {
            $t = $arr[$i]; 
            $arr[$i] = mb_substr($t, $left);
            $left -= (mb_strlen ($t) - mb_strlen ($arr[$i]));
          }
          
          if ($left <= 0) {
            if ($length > 0) {
              $t = $arr[$i];
							if ($length < mb_strlen($t)) {
								$regex = '/\w+(\W+\w*)$/';
								$t = mb_substr($t, 0, $length+1);
								if (preg_match ($regex, $t, $matches)) {
									$arr[$i] = mb_substr($t, 0, $length + 1 - mb_strlen($matches[1]));
								}
								$length = 0;
							} else {
								$arr[$i] = mb_substr($t, 0, $length);
								$length -= mb_strlen ($arr[$i]);
							}							
              //$arr[$i] = mb_substr($t, 0, $length);
              if ($length <= 0) {
                $arr[$i] .= '...';
              }
              
            } else {
              $arr[$i] = '';
            }
          }
        }else{
          if (SmartTrim::isHiddenTag ($arr[$i], $hiddenClasses)) {
            if ($endTag = SmartTrim::getCloseTag($arr, $i)){
              while ($i<$endTag) $strout .= $arr[$i++]."\n";
            }
          }
        }
        $strout .= $arr[$i]."\n";
      }
      //echo $strout;  
      return SmartTrim::toString($arr, $len);
    }

    function trim($strin, $pos=0, $len=10000, $hiddenClasses = '' ) {
      $strout = trim($strin);
      
      $pattern = '/(<[^>]*>)/';
      $arr = preg_split($pattern, $strout, -1, PREG_SPLIT_DELIM_CAPTURE);
      $left = $pos;
      $length = $len;
      $strout = '';
      for($i=0;$i<count($arr);$i++) {
        $arr[$i] = trim($arr[$i]);
        if ($arr[$i] == '') continue;
        if ($i % 2 == 0) {
          if($left > 0) {
            $t = $arr[$i]; 
            $arr[$i] = substr($t, $left);
            $left -= (strlen ($t) - strlen ($arr[$i]));
          }
          
          if ($left <= 0) {
            if ($length > 0) {
              $t = $arr[$i];
							if ($length < strlen($t)) {
								$regex = '/\w+(\W+\w*)$/';
								$t = substr($t, 0, $length+1);
								if (preg_match ($regex, $t, $matches)) {
									$arr[$i] = substr($t, 0, $length + 1 - strlen($matches[1]));
								}
								$length = 0;
							} else {
								$arr[$i] = substr($t, 0, $length);
								$length -= strlen ($arr[$i]);
							}
              if ($length <= 0) {
                $arr[$i] .= '...';
              }
              
            } else {
              $arr[$i] = '';
            }
          }
        }else{
          if (SmartTrim::isHiddenTag ($arr[$i], $hiddenClasses)) {
            if ($endTag = SmartTrim::getCloseTag($arr, $i)){
              while ($i<$endTag) $strout .= $arr[$i++]."\n";
            }
          }
        }
        $strout .= $arr[$i]."\n";
      }
      //echo $strout;  
      return SmartTrim::toString($arr, $len);
    }
    
    function isHiddenTag ($tag, $hiddenClasses='') {
      //By pass full tag like img
      if (substr($tag, -2)=='/>') return false;
      if (in_array(SmartTrim::getTag($tag), array('script','style'))) return true;
      if (preg_match('/display\s*:\s*none/', $tag)) return true;
      if ($hiddenClasses && preg_match('/class\s*=[\s"\']*('.$hiddenClasses.')[\s"\']*/', $tag)) return true;
    }
    
    function getCloseTag ($arr, $openidx) {
      $tag = trim($arr[$openidx]);
      if(!$openTag = SmartTrim::getTag($tag)) return 0;
      
      $endTag = "</$openTag>";
      $endidx = $openidx+1;
      $i=1;
      while ($endidx<count($arr)) {
        if (trim($arr[$endidx]) == $endTag) $i--;
        if (SmartTrim::getTag($arr[$endidx])==$openTag) $i++;
        if ($i == 0) return $endidx;
        $endidx++;
      }
      return 0;
    }
    
    function getTag ($tag) {
      if (preg_match ('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches)) return ''; //full tag
      if (preg_match ('/\A<([^ \/>]*)([^>]*)>\Z/', trim($tag), $matches)) {
        //echo "[".strtolower($matches[1])."]";
        return strtolower($matches[1]);
      }
      //if (preg_match ('/<([^ \/>]*)([^\/>]*)>/', trim($tag), $matches)) return strtolower($matches[1]);
      return '';
    }
    
    function toString ($arr, $len) {
      $i = 0;
      $stack = new JAStack();
      $length = 0;
      while ($i<count($arr)) {
        $tag = trim($arr[$i++]); 
        if ($tag == '') continue;
        if (SmartTrim::isCloseTag ($tag)){
          if ($ltag = $stack->getLast()){
            if ('</'.SmartTrim::getTag($ltag).'>' == $tag) $stack->pop();
            else $stack->push($tag);
          }
        } else if (SmartTrim::isOpenTag ($tag)) {
          $stack->push ($tag);
        } else if (SmartTrim::isFullTag ($tag)) {
          //echo "[TAG: $tag, $length, $len]\n";
          if ($length < $len)
            $stack->push ($tag);
        } else {
          $length += strlen ($tag);
          $stack->push ($tag);     
        }
      }
      
      return $stack->toString();
    }

    function isOpenTag ($tag) {
      if (preg_match ('/\A<([^\/>]+)\/>\Z/', trim($tag), $matches)) return false; //full tag
      if (preg_match ('/\A<([^ \/>]+)([^>]*)>\Z/', trim($tag), $matches)) return true;
      return false;
    }
    
    function isFullTag ($tag) {
      //echo "[Check full: $tag]\n";
      if (preg_match ('/\A<([^\/>]*)\/>\Z/', trim($tag), $matches)) return true; //full tag
      return false;
    }
    
    function isCloseTag ($tag) {
      if (preg_match ('/<\/(.*)>/', $tag)) return true;
      return false;
    }    
  }
  
class JAStack {
    var $_arr = null;
    function JAStack() {
      $this->_arr = array();
    }
    
    function push ($item) {
      $this->_arr[count($this->_arr)] = $item;
    }
    function pop () {
      if (!$c = count($this->_arr)) return null;
      $ret = $this->_arr[$c-1];
      unset ($this->_arr[$c-1]);
      return $ret;
    }
    function getLast() {
      if (!$c = count($this->_arr)) return null;
      return $this->_arr[$c-1];
    }
    function toString() {
      $output = '';
      foreach ($this->_arr as $item) {
        $output .= $item."\n";      	
      }
      return $output;
    }
  }	