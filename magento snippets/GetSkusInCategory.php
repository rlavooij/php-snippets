<?php
require_once 'app/Mage.php';
umask(0);
Mage::app('default');
$cat_id = 2117;
$skus = array();
$category = Mage::getModel('catalog/category')->load($cat_id);
$collection = $category->getProductCollection()->addAttributeToSort('position');
Mage::getModel('catalog/layer')->prepareProductCollection($collection);
foreach ($collection as $product) {
	$product_id = $product->getId();
	$_product = Mage::getModel('catalog/product')->load($product_id);
	$skus[] = $_product->getSku();
}
$sku_list = implode(',', $skus);
echo $sku_list;