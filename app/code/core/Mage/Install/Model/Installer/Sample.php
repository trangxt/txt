<?php
/**
 * Sample DB Installer
 *
 * @category   Mage
 * @package    Mage_Install
 * @author     JoomlArt.com
 */
class Mage_Install_Model_Installer_Sample extends Mage_Install_Model_Installer_Db
{
    /**
     * Instal Sample database
     *
     * $data = array(
     *      [db_host]
     *      [db_name]
     *      [db_user]
     *      [db_pass]
     * )
     *
     * @param array $data
     */
    public function installSampleDB ($data) {
        $config = array(
            'host'      => $data['db_host'],
            'username'  => $data['db_user'],
            'password'  => $data['db_pass'],
            'dbname'    => $data['db_name']
        );
        $connection = Mage::getSingleton('core/resource')->createConnection('core_setup', $this->_getConnenctionType(), $config);

        $installer = new Mage_Core_Model_Resource_Setup('core_setup');
		$installer->startSetup();
		
		//Get content from sample data
		//Default sample data
		//$tablePrefix = (string)Mage::getConfig()->getTablePrefix();
		$tablePrefix = $data['db_prefix'];
		$base_url = $data['unsecure_base_url'];
		$base_surl = $base_url;
		if (!empty($data['use_secure'])) $base_surl = $data['secure_base_url'];
		
		/* Run sample_data.sql if found, by pass default sample data from Magento */
		
		$file = Mage::getConfig()->getBaseDir().'/sql/sample_data.sql';
		if (is_file($file) && ($sqls = file_get_contents ($file))) {
			$sqls = str_replace ('#__', $tablePrefix, $sqls);
			$installer->run ($sqls);
		}else{			
			$file = Mage::getConfig()->getBaseDir().'/sql/magento_sample_data_for_1.2.0.sql';
			if (is_file($file) && ($sqls = file_get_contents ($file))) {
				$sqls = str_replace ('#__', $tablePrefix, $sqls);
				$installer->run ($sqls);
			}			
		}
		
		$installer->run ("
			UPDATE `{$tablePrefix}core_config_data` SET `value`='$base_url' where `path`='web/unsecure/base_url';
			UPDATE `{$tablePrefix}core_config_data` SET `value`='$base_surl' where `path`='web/secure/base_url';
		"
		);
		
		$installer->endSetup();
    }
}