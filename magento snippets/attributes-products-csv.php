<?php
require_once('app/Mage.php');
umask(0);
Mage::app('default');
$db_conn = Mage::getSingleton('core/resource');
$r_conn = $db_conn->getConnection('core_read');
$sql = "SELECT sku FROM catalog_product_entity WHERE type_id = 'simple'";
$results = $r_conn->fetchAll($sql);
foreach ($results as $row) {
	grab_attributes($row['sku']);
}
function grab_attributes($sku) {
	global $db_conn, $r_conn, $csv;
$sql = <<<"SQL"
SELECT * FROM (
	SELECT sku,
		MAX(CASE WHEN attribute_code = 'om_division' THEN value_code END) om_division,
		MAX(CASE WHEN attribute_code = 'is_professional_use_only' THEN value_code END) is_professional_use_only,
		MAX(CASE WHEN attribute_code = 'is_reviewable' THEN value_code END) is_reviewable,
		MAX(CASE WHEN attribute_code = 'status' THEN value_code END) status
		FROM (
			SELECT e.sku, ea.attribute_code, eav.value AS 'value_code'
			FROM catalog_product_entity e
			JOIN catalog_product_entity_varchar eav
			  ON e.entity_id = eav.entity_id
			JOIN eav_attribute ea
			  ON eav.attribute_id = ea.attribute_id
			WHERE e.sku = $sku
			AND ea.attribute_code = 'om_division'
			UNION
			SELECT e.sku, ea.attribute_code, eav.value AS 'value_code'
			FROM catalog_product_entity e
			JOIN catalog_product_entity_int eav
			  ON e.entity_id = eav.entity_id
			JOIN eav_attribute ea
			  ON eav.attribute_id = ea.attribute_id
			AND ea.attribute_code IN ('is_professional_use_only', 'is_reviewable', 'status')
			WHERE e.sku = $sku
		) inner_query
	) main_query
WHERE status = 1
SQL;
	$row = $r_conn->fetchAll($sql)[0];
	echo '"' . $row['sku'] . '","' . $row['om_division'] . '","' . $row['is_professional_use_only'] . '","' . $row['is_reviewable'] . '"' . PHP_EOL;
	
}
