<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT s.type_no, sd.service_name FROM `servicetype` AS s JOIN `servicetype_description` AS sd ON s.servicetype_no=sd.service_number  WHERE s.service_no='".$_GET['service_no']."'  AND sd.language_code='eng'";
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["type_no"]=$row[0];
	$a_json_row["type_name"]=$row[1];
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].'('.json_encode($a_json).')';
?>
