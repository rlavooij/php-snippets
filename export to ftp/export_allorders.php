<?php
//
//this file is the "old version", as client wanted to continue with per order version of the export. so this file is a bit messy, but works.
//

include('db.php');
$datetime=date("Y_m_d_His");
$end = 0;
$con = getdb();
//the query, joining 3 tables and selecting by profile_id. grouped by reference_id
$query ="SELECT * from table1 as t1 INNER JOIN table2 as t2 On t1.profile_id=t2.profile_id INNER JOIN table3 as t3 On t1.profile_id=t3.profile_id group by reference_id";
$result = mysqli_query($con, $query);
// while($row = mysqli_fetch_assoc($result)){
//   fputcsv($output, $row);
// }
$i=0;
$len = count($result);
foreach ($result as $row => $value) {

if( $value['exported'] != 1 ){

  // first
  if ($i == 0) {
    $csv_filename = "export_" .$datetime.".csv"; 
    $output = fopen("/public_html/export/" .$csv_filename, "x+");

    fputcsv($output, array(
      'accountid',
      'branchid',
      'altdel',
      'order_id',
      'continuation',
      'ordered',
      'bill_title',
      'bill_fullname',
      'bill_address1',
      'bill_address2',
      'bill_address3',
      'bill_city',
      'bill_postcode',
      'bill_region',
      'bill_country_name',
      'bill_telephone',
      'bill_mobile',
      'bill_email',
      'del_title',
      'del_fullname',
      'del_address1',
      'del_address2',
      'del_address3',
      'del_city',
      'del_postcode',
      'del_region',
      'del_country_name',
      'del_telephone',
      'del_mobile',
      'del_email',
      'code',
      'sku',
      'quantity',
      'product',
      'shipping',
      'merchant_value',
      'personalised'

      ));
  }
  // last
  else if ($i == $len - 1) {
      fclose($output);
  }
    $street = $value['street'];
    echo $street;
    $add = preg_split('/\n/', $street);
    $address1 = $add[0];
    $address2 = $add[1];
  $data =array(
    'HARRYH',// accountid
    '',// branchid
    '1',// altdel
    $value['reference_id'],// order_id
    'no',// continuation
    $value['created_at'],// ordered
    '',// del_title
    $value['customer_fullname'],// del_fullname
    $value['customer_fullname'],// del_address1
    $address1,// del_address2
    $address2,// del_address3
    $value['city'],// del_city
    $value['postcode'],// del_postcode
    $value['region'],// del_region
    $value['country_id'],// del_country_name
    $value['telephone'],// del_telephone
    $value['telephone'],// del_mobile
    $value['customer_email'],// del_email
    '',// del_title
    $value['customer_fullname'],// del_fullname
    $value['customer_fullname'],// del_address1
    $address1,// del_address2
    $address2,// del_address3
    $value['city'],// del_city
    $value['postcode'],// del_postcode
    $value['region'],// del_region
    $value['country_id'],// del_country_name
    $value['telephone'],// del_telephone
    $value['telephone'],// del_mobile
    $value['customer_email'],// del_email
    '',// code
    $value['sku'],// sku
    $value['qty'],// quantity
    '',// product
    '',// shipping
    '',// merchant_value
    'Membership'// personalised
    // $value['status'],// status
    // $value['billing_period'],// billing period
    // $value['billing_frequency'],// billing frequency
    // $value['customer_id'],// customer_id
    // $value['customer_dob'],// customer_dob
    // $value['regular_price']// regular_price
  );
  fputcsv($output, $data);
  // set exported to 1
  $query_selector = $value['profile_id'];
  $query2 = "UPDATE table1 set exported='1' WHERE profile_id='$query_selector' ";
  $result2 = mysqli_query($con, $query2);
  $i++;
}
}
// Make ftp connection
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
echo "Connected as $ftp_user@$ftp_server <br />";
ftp_pasv($conn_id, true);
 $fp = "/public_html/export/" .$csv_filename;
  if (ftp_put($conn_id, 'membership_export/' . $csv_filename, $fp, FTP_ASCII)) {
     echo "successfully uploaded $fp\n";
    } else {
     echo "There was a problem while uploading $fp\n";
    }
ftp_close($conn_id);
echo "file saved " . $csv_filename;
} else {
echo "Couldn't connect as $ftp_user\n";
}

?>
