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

// no direct access
include_once (dirname(__FILE__).DS.'/includes/templatetools.php');

# BEGIN: TEMPLATE CONFIGURATIONS ##########
####################################
$_params = new JParameter();
# Joomla menutype used in main navigation
# LOGO TYPE DESCRIPTION
$_params->set('logoType','Text');//image: Image; text: Text
#LOGO TEXT DESCRIPTION
$_params->set('logoText','Logo Text');
#SLOGAN DESCRIPTION
$_params->set('sloganText','Slogan'); 
#FONT SIZE DESCRIPTION
$_params->set('ja_font','3');//value from 1 to 6
#Color
$_params->set('ja_color','default');//default
#TEMPLATE WIDTH DESCRIPTION
$_params->set('ja_screen','wide');//narrow:Narrow Screen; wide:Wide Screen;
#MENU'S TYPE
$_params->set('ja_menu','css');//css:CSS Menu; moo:Moo Menu;

$_params->set('usertool_font', 0); //0: disable, 2: show font tools
$_params->set('usertool_color', 0); //0: disable, 4: show colors tools
# END: TEMPLATE CONFIGURATIONS ##########

global $tmpTools;
if (defined('_DEMO_MODE_')) {
	$tmpTools = new JA_Tools('jm_mesolite', $_params, array(JA_TOOL_MENU, JA_TOOL_COLOR));	
	$tmpTools->checkHomepage();
} else {
	$tmpTools = new JA_Tools('jm_mesolite', $_params);
}

#Supported colors
$tmpTools->setColorThemes (array('default'));

/*
//Main navigation
$ja_menutype = $tmpTools->getParam(JA_TOOL_MENU, 'css');
$jamenu = null;
if ($ja_menutype != 'none') {
include_once( dirname(__FILE__).DS.'ja_menus/Base.class.php' );
$japarams = new JParameter();
$japarams->set( 'menutype', $tmpTools->getParam('menutype', 'mainmenu') );
$japarams->set( 'menu_images_align', 'left' );
$japarams->set( 'menupath', $tmpTools->templateurl() .'/ja_menus');
$japarams->set('menu_title', 0);
switch ($ja_menutype) {
	case 'moo':
		$menu = "Moomenu";
		include_once( dirname(__FILE__).DS.'ja_menus/'.$menu.'.class.php' );
		break;
	case 'css':
	default:
		$menu = "CSSmenu";
		include_once( dirname(__FILE__).DS.'ja_menus/'.$menu.'.class.php' );
		break;
}
$menuclass = "JA_$menu";
$jamenu = new $menuclass ($japarams);
//End for main navigation
*/
?>
