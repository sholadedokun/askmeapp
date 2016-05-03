<?php
// contains utility functions mb_stripos_all() and apply_highlight()
//require_once 'local_utils.php';
require_once 'fun_connect2.php';

//error_reporting(E_ERROR);
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

$sql = "SELECT distinct p_description_id, p_description_Name FROM producttype_description WHERE (";
for($i = 0; $i < $p; $i++) {
  if($i != 0){$sql .= " OR ( p_description_Name LIKE '%".mysql_real_escape_string($parts[$i])."%')";}
  else{
	  $sql .= "( p_description_Name LIKE '%".mysql_real_escape_string($parts[$i])."%')";
  }
}
  $sql .= " )";
//  echo($sql);
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
  $a_json_row["id"] = $row[0];
  $a_json_row["type"] = 'P';
  $a_json_row["value"] = $row[1];
  $a_json_row["label"] = $row[1];
  array_push($a_json, $a_json_row);
}

$sql = "SELECT distinct service_name FROM service WHERE (";
for($i = 0; $i < $p; $i++) {
  if($i != 0){$sql .= " OR ( service_name LIKE '%".mysql_real_escape_string($parts[$i])."%')";}
  else{
	  $sql .= "( service_name LIKE '%".mysql_real_escape_string($parts[$i])."%')";
  }
}
  $sql .= " )";
//  echo($sql);
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
  $a_json_row["id"] = $row[0];
  $a_json_row["type"] = 'S';
  $a_json_row["value"] = $row[0];
  $a_json_row["label"] = $row[0];
  array_push($a_json, $a_json_row);
}
$sql = "SELECT distinct industry_name FROM service_industry WHERE (";
for($i = 0; $i < $p; $i++) {
  if($i != 0){$sql .= " OR ( industry_name LIKE '%".mysql_real_escape_string($parts[$i])."%')";}
  else{
	  $sql .= "( industry_name LIKE '%".mysql_real_escape_string($parts[$i])."%')";
  }
}
  $sql .= " )";
//  echo($sql);
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
  $a_json_row["id"] = $row[0];
  $a_json_row["type"] = 'I';
  $a_json_row["value"] = $row[0];
  $a_json_row["label"] = $row[0];
  array_push($a_json, $a_json_row);
}

$json = json_encode($a_json);
echo $_GET['callback'].'('.$json.')';
?>
