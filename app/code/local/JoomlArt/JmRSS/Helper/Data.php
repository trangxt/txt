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


class JoomlArt_JmRSS_Helper_Data extends Mage_Core_Helper_Abstract {		
	
	const CACHE_TAG = 'jm-rss-dataconfig';
	
	function init($attributes) {  
		$data = array();
		if(isset($attributes['cachelife'])){
			$timecache =  $attributes['cachelife'];
        }else {		
           	$timecache = Mage::getStoreConfig("joomlart_jmrss/joomlart_jmrss_cache/cachelife");
	  	}
	  	$data['timecache'] =  $timecache;
	  	if(isset($attributes['jmodeCache'])){
			$cachemode =  $attributes['jmodeCache'];
        }else {		
           	$cachemode =Mage::getStoreConfig("joomlart_jmrss/joomlart_jmrss_cache/jmodeCache");
	  	}
	  	
		
		if($cachemode == 0){		
			Mage::app()->cleanCache();
		}
		
		if ($cachemode && $data = Mage::app()->loadCache('jm-rss-dataconfig')) {
		   	$data = unserialize($data);	
		   	$data['cachemode'] =  $cachemode;	
		   	$data['timecache'] =  $timecache;
		} 
		else {
			//$arrParams = array('show', 'title', 'seturl', 'aquanlity', 'showLink', 'maxchars', 'showDate', 'date_format', 'showImage', 'autoresize', 'width', 'height', 'folderImage', 'show_introdure');
			$arrParams = array('show', 'title', 'seturl', 'aquanlity', 'showLink', 'maxchars', 'showDate', 'date_format', 'show_introduce', 'support_html');
			foreach ($arrParams as $var){
				if(isset($attributes[$var])){
					$data[$var] =  $attributes[$var];
		        }else {		
		           	$data[$var] =Mage::getStoreConfig("joomlart_jmrss/joomlart_jmrss/$var");
			  	}	
			}
					      					
			Mage::app()->saveCache(serialize($data), 'jm-rss-dataconfig', array(self::CACHE_TAG), $timecache);			
		}
			
		return sizeof($data) > 0 ? $data : 0;
    }

}


?>