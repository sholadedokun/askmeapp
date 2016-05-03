<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$s_para=json_decode($_GET['searchP']);
// replace multiple spaces with one
$term=str_replace(",", " ", $_GET['searchDesc']);
$term = preg_replace('/\s+/', ' ', $_GET['searchDesc']);


// replace multiple spaces with one
$loc=str_replace(",", " ", $_GET['searchLocation']);
$loc = preg_replace('/\s+/', ' ', $_GET['searchLocation']);

// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
if(preg_match("/[^\040\pL\pN_-]/u", $term)) {  exit;}
// *****************************************************************************

// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
if(preg_match("/[^\040\pL\pN_-]/u", $loc)) {  exit;}
// *****************************************************************************

$Desparts = explode(' ', $term); $Dp = count($Desparts);
$Locparts = explode(' ', $loc); $Lp = count($Locparts);
$sql = "SELECT s.*,  pd.p_description_Name FROM `producttype_description` AS pd JOIN `producttype` AS p ON pd.p_description_id=p.product_no JOIN `service` as s ON p.product_parent=s.service_no  WHERE ";

for($a=0; $a<$Dp; $a++){
	if($_GET['searchType']=='P'){
		if($a == 0){$sql.='( pd.p_description_Name LIKE '."'%".mysql_real_escape_string($Desparts[$a]) . "%'";}
		else{$sql.=' OR pd.p_description_Name LIKE '."'%".mysql_real_escape_string($Desparts[$a]) . "%'";}
	}
	elseif($_GET['searchType']=='S'){
		if($a == 0){$sql.='( s.service_Name LIKE '."'%".mysql_real_escape_string($Desparts[$a]) . "%'";}
		else{$sql.=' OR s.service_Name LIKE '."'%".mysql_real_escape_string($Desparts[$a]) . "%'";}
	}
	else{
		if($a == 0){$sql.='( s.service_industry LIKE ' . "'%" . mysql_real_escape_string($Desparts[$a]) . "%'";}
		else{$sql.=' OR s.service_industry LIKE ' . "'%" . mysql_real_escape_string($Desparts[$a]) . "%'";}
	}

}
$sql.=')';

for($a=0; $a<$Lp; $a++){
	if($a == 0){$sql.=' AND (s.route_street LIKE '."'%".mysql_real_escape_string($Locparts[$a]) . "%' OR s.neighbourhood LIKE ". "'%". mysql_real_escape_string($Locparts[$a])."%' OR s.service_city LIKE "."'%".mysql_real_escape_string($Locparts[$a])."%' OR s.service_province LIKE ". "'%". mysql_real_escape_string($Locparts[$a])."%' OR s.service_country LIKE "."'%".mysql_real_escape_string($Locparts[$a]). "%'";}
	else{$sql.=' OR s.route_street LIKE '."'%".mysql_real_escape_string($Locparts[$a]) . "%' OR s.neighbourhood LIKE ". "'%". mysql_real_escape_string($Locparts[$a])."%' OR s.service_city LIKE "."'%".mysql_real_escape_string($Locparts[$a])."%' OR s.service_province LIKE ". "'%". mysql_real_escape_string($Locparts[$a])."%' OR s.service_country LIKE "."'%".mysql_real_escape_string($Locparts[$a]). "%'";}
}
$sql.=')';
//echo $sql;
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["se_no"]=$row[0];
	$a_json_row["se_name"]=$row[1];
	$a_json_row["se_ind"]=$row[2];
	$a_json_row["se_routeStreet"]=$row[3];
	$a_json_row["se_neighbour"]=$row[4];
	$a_json_row["se_city"]=$row[5];
	$a_json_row["se_province"]=$row[6];
	$a_json_row["se_country"]=$row[7];
	$a_json_row["se_dateC"]=$row[9];
	$a_json_row["se_author"]=$row[10];
	$a_json_row["se_product"]=$row[10];
	$sqlp="Select p.post_content FROM `post` AS p JOIN `servicetype` AS st ON p.post_parent=st.type_no JOIN `service` AS s ON st.service_no=s.service_no   where s.service_no='$row[0]' and p.content_type='.r.'";
	$rsp = mysql_query($sqlp);
	$s_p=0;
	$sp_a=0;
	while($rowp = mysql_fetch_array($rsp)) {
		$s_p+=$rowp[0]; $sp_a++;
	}
	$rate=0;
	if($sp_a>0){$rate=$s_p/$sp_a;}
	$a_json_row["se_rate"]=$rate;
	$a_json_row["se_totalR"]=$sp_a;
	array_push($a_json, $a_json_row);
}

$json= json_encode($a_json);
echo $_GET['callback'].'('.$json.')';
?>
