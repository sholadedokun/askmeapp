<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1); 
 
$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT userLastName, userFirstName  FROM `registered_user` WHERE userId='".$_GET['userNo']."'";

$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["lname"]=$row[0]; 
	$a_json_row["fname"]=$row[1];
	$a_json_row["user_no"]=$_GET['userNo']; 
	array_push($a_json, $a_json_row);
}
echo json_encode($a_json);
?>