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

class TTS_Baokim_Model_Mysql4_Baokim_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('baokim/baokim');
    }
}