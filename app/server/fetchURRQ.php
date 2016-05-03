<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1); 
 
$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT s.service_name, s.service_no, p.post_id, p.post_content,  p.post_type, p.date_time, st.servicetype_no, content_type FROM `service` AS s JOIN `post` AS p ON s.service_no=p.service_no JOIN `servicetype` as st ON p.post_parent= st.type_no  WHERE p.service_no='".$_GET['service_no']."'  AND p.post_author='".$_GET['author']."'";
$rs = mysql_query($sql);

$same_serv='';
$i=0;
while($row = mysql_fetch_array($rs)) {
	
		if($row[6]==$same_serv){
		$sqlx="Select service_name FROM servicetype_description WHERE `service_number`='$row[6]'";
			$rsx = mysql_query($sqlx);
			while($rowx = mysql_fetch_array($rsx)) {
				$new_cat=array(postid=>$row[2], postcontent=>$row[3], posttype=>$row[4], postdate=>$row[5], content_type=>$row[7], category_name=>$rowx[0]); 
			}
			array_push($a_json_row["category"], $new_cat);
			array_push($a_json[$i-1]["category"], $new_cat);
		}
		else{
			$same_serv=$row[6];
			$a_json_row["service_name"]=$row[0]; 
			$sqlx="Select service_name FROM servicetype_description where `service_number`='$row[6]'";
			$rsx = mysql_query($sqlx);
			while($rowx = mysql_fetch_array($rsx)) {
				$new_cat=array(postid=>$row[2], postcontent=>$row[3], posttype=>$row[4], postdate=>$row[5], content_type=>$row[7], category_name=>$rowx[0]); 
			}
			$a_json_row["category"]=array();
			array_push($a_json_row["category"], $new_cat);
			$i++;
			array_push($a_json, $a_json_row);
		}
	
	
}
echo json_encode($a_json);
?>
