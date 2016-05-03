<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1); 
 
$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT DISTINCT service_no FROM `post` WHERE post_author='".$_GET['author_no']."'";

$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["s_id"]=$row[0]; 
	array_push($a_json, $a_json_row);
}
echo json_encode($a_json);
?>