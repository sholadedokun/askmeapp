<?php 
  $url = $_GET['url'];
  echo json_encode(file_get_contents($url)); 
 ?>