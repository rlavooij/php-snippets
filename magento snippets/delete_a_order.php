<?php
require_once('app/Mage.php');
umask(0);
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::getModel('sales/order')->loadByIncrementId('200000065')->delete();