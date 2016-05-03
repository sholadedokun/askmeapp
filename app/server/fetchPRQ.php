<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT post_id, post_content,  post_type, date_time, post_author, content_type  FROM `post` WHERE post_parent='".$_GET['parent_no']."'  AND post_type='".$_GET['p_type']."' order by post_id desc";

$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["p_id"]=$row[0];
	$a_json_row["p_cont"]=$row[1];
	$a_json_row["p_type"]=$row[2];
	$a_json_row["dt"]=$row[3];
	$a_json_row["p_author"]=$row[4];
	$a_json_row["p_ctype"]=$row[5];
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].'('.json_encode($a_json).')';
?>
