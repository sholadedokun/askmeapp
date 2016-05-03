<?php
// contains utility functions mb_stripos_all() and apply_highlight()
require_once 'local_utils.php';
require_once 'fun_connect2.php';

error_reporting(E_ERROR);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);



// get what user typed in autocomplete input
$term = trim($_GET['term']);

$a_json = array();
$a_json_row = array();

$a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
$json_invalid = json_encode($a_json_invalid);

// replace multiple spaces with one
$term=str_replace(",", " ", $term);
$term = preg_replace('/\s+/', ' ', $term);

// SECURITY HOLE ***************************************************************
// allow space, any unicode letter and digit, underscore and dash
if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
  print $json_invalid;
  exit;
}
// *****************************************************************************

$parts = explode(' ', $term);
$p = count($parts);

/**
 * Create SQL
 */
/*SELECT c.NAME, d.Name FROM country_ids AS c Join destinations As e ON c.countrycode= e.countrycode Join destination_ids As d ON e.destinationcode=d.destinationcode WHERE (d.Name like '%dub%' OR c.Name like '%dub%' ) AND c.languagecode='ENG' AND d.languagecode='ENG'*/

$sql = "SELECT s.service_no, s.service_name, s.service_industry,  s.route_street, s.neighbourhood, s.service_city, s.service_province, s.service_country, pt.product_no,  ptd.p_description_Name FROM service AS s Join producttype As pt ON s.service_no= pt.product_parent Join producttype_description As ptd ON pt.product_no = ptd.p_description_id  WHERE (";
for($i = 0; $i < $p; $i++) {
  if($i != 0){$sql .= ' AND ( s.service_name LIKE ' . "'%" . mysql_real_escape_string($parts[$i]) . "%' OR s.service_industry LIKE ". "'%". mysql_real_escape_string($parts[$i]) . "%' OR ptd.p_description_name LIKE ". "'%" . mysql_real_escape_string($parts[$i]) . "%')";}
  else{
	  $sql .= '( s.service_name LIKE ' . "'%" . mysql_real_escape_string($parts[$i]). "%' OR s.service_industry LIKE ". "'%" . mysql_real_escape_string($parts[$i]) . "%' OR  ptd.p_description_name LIKE ". "'%" . mysql_real_escape_string($parts[$i]) . "%')";
  }
}
  $sql .= " )";
//  echo($sql);
$rs = mysql_query($sql);
/*if($rs === false) {
  //$user_error = 'Wrong SQL: ' . $sql . 'Error: ' . mysql_errno . ' ' . mysql_error;
//  trigger_error($user_error, E_USER_ERROR);
} */
while($row = mysql_fetch_array($rs)) {
  $a_json_row["id"] = $row[0];
  $a_json_row["value"] = $row[1]." (".$row[2].')';
  $a_json_row["label"] = $a_json_row["value"];
  $a_json_row["type"] = 'review';
  if($row[3]!=''){$a_json_row["label"].=" $row[3],";}
  if($row[4]!=''){$a_json_row["label"].=" $row[4],";}
  if($row[5]!=''){$a_json_row["label"].=" $row[5],";} 
  if($row[6]!=''){$a_json_row["label"].=" $row[6],";}
  if($row[7]!=''){$a_json_row["label"].=" $row[7].";}
  $a_json_row["n"] = $row[1];
  $a_json_row["i"] = $row[2];
  $a_json_row["a"] = $row[3];
  $a_json_row["a2"] = $row[4];
  $a_json_row["a3"] = $row[5];
  $a_json_row["a4"] = $row[6];
  $a_json_row["a5"] = $row[7];


  array_push($a_json, $a_json_row);
}
$a_json_row["id"] = 0;
  $a_json_row["value"] = $_GET['term'];
  $a_json_row["type"] = 'review';
  $a_json_row["label"] = 'Add A New Service, Business or Organisation';
  
  array_push($a_json, $a_json_row);


echo $_GET['callback'].'('.json_encode($a_json).')';
?>
