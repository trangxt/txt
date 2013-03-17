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

class TTS_Baokim_Model_Mysql4_Baokim extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the baokim_id refers to the key field in your database table.
        $this->_init('baokim/baokim', 'baokim_id');
    }
}