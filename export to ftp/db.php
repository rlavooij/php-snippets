<?php
function getdb(){
//database to get info from
  $servername = "localhost";
  $username = "username";
  $password = "userpass";
  $db = "database";
  try {
    $conn = mysqli_connect($servername, $username, $password, $db);
     //echo "Connected successfully";
  }
  catch(exception $e){
    echo "Connection failed: " . $e->getMessage();
    }
  return $conn;
}
//External ftp settings
$ftp_server = "external host";
$ftp_user = "username";
$ftp_pass = "password";
?>
