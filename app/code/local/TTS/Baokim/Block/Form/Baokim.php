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

class TTS_Baokim_Block_Form_Baokim extends Mage_Payment_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/baokim.phtml');
    }

}
