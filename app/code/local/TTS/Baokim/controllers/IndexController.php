<?php
/**
 * Magento Viet
 *
 * @copyright  Copyright (c) 2011 by TTS Tech (http://www.ttstech.com)
 * @email support support@magentovietnam.com
 * @license    http://www.magentovietnam.com/license
 */
?>

<?php
class TTS_Baokim_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
			
		$this->loadLayout();     
		$this->renderLayout();
    }
}