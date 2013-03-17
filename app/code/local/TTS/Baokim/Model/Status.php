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

class TTS_Baokim_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('baokim')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('baokim')->__('Disabled')
        );
    }
}