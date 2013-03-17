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

class JoomlArt_JmSlideshow_Helper_Data extends Mage_Core_Helper_Abstract {		
	function get( $attributes )
	{
		$data = array();
		$arrayParams = array('show', 
		                     'title', 
							 'folder', 
							 'startItem', 
							 'mainWidth', 
							 'mainHeight', 
							 'showdesc', 
							 'showdescwhen',
							 'readmoretext', 
							 'duration', 
							 'animation', 
							 'navigationAlignment',
							 'navigation',
							 'thumbWidth', 
							 'thumbHeight', 
							 'thumbSpace', 
							 'showItem', 
							 'control', 
							 'autoplay', 
							 'interval', 
							 'thumbOpacity', 
							 'descOpacity', 
							 'overlapOpacity', 
							 'description' ,
							  'thumbnailMode',
							 'useRatio',
							 'useRatios',
							 'source',
							 'sourceProductsMode',
							 'catsid',
							 'quanlity',
							 'navItemHeight',
							 'navItemWidth'
		);
		
		foreach ($arrayParams as $var)
		{
			if(isset($attributes[$var]))
			{
				$data[$var] =  $attributes[$var];
			}		
	    	$data[$var] =  Mage::getStoreConfig("joomlart_jmslideshow/joomlart_jmslideshow/$var");
		}
				
    	return $data;
	}	  
}
?>