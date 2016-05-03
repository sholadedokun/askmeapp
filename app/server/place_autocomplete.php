<?php
// contains utility functions mb_stripos_all() and apply_highlight()
//require_once 'local_utils.php';
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

/*
 * Create SQL
 */


$sql = "SELECT distinct place_id, route_street, neighbourhood, service_city, service_province, service_country FROM service WHERE (";
for($i = 0; $i < $p; $i++) {
  if($i != 0){$sql .= ' OR ( route_street LIKE '."'%".mysql_real_escape_string($parts[$i]) . "%' OR neighbourhood LIKE ". "'%". mysql_real_escape_string($parts[$i])."%' OR service_city LIKE "."'%".mysql_real_escape_string($parts[$i])."%' OR service_province LIKE ". "'%". mysql_real_escape_string($parts[$i])."%' OR service_country LIKE "."'%".mysql_real_escape_string($parts[$i]). "%')";}
  else{
	  $sql .= '( route_street LIKE ' . "'%" . mysql_real_escape_string($parts[$i]) . "%' OR neighbourhood LIKE ". "'%". mysql_real_escape_string($parts[$i]).  "%' OR service_city LIKE ". "'%". mysql_real_escape_string($parts[$i])."%' OR service_province LIKE ". "'%". mysql_real_escape_string($parts[$i]). "%' OR service_country LIKE ". "'%". mysql_real_escape_string($parts[$i]). "%')";
  }
}
  $sql .= " )";
//  echo($sql);
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
  $a_json_row["id"] = $row[0];
  $a_json_row["value"] = $row[1]." ".$row[2];
  $a_json_row["type"] = 'search';
  $a_json_row["label"] = $row[1]." ".$row[2]." ".$row[3]." ".$row[4]." ".$row[5];
  array_push($a_json, $a_json_row);
}

$json = json_encode($a_json);
 echo $_GET['callback'].'('.$json.')';
?>
