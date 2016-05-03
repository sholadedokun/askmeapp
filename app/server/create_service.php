<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
	require_once 'fun_connect2.php';
	$au=$_GET['user'];
	$ptype=$_GET['type'];
	$t=time();
	$post=json_decode($_GET['post']);
	$res;		
	$sql="INSERT INTO service VALUES (NULL, 
								'".mysql_real_escape_string($post->Name)."', 
								'".mysql_real_escape_string($post->Industry)."', 
								'".mysql_real_escape_string($post->State)."', 
								'".mysql_real_escape_string($post->Local_government)."',
								'".mysql_real_escape_string($post->Location)."',
								$t,
								'".mysql_real_escape_string($au)."'
												
	)";
	$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());
	$user_id = mysql_insert_id();
	$sql="INSERT INTO producttype VALUES (NULL, 
								'3', 
								'".$user_id."', 
								'".mysql_real_escape_string($au)."', 
								$t											
	)";
	$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());
	if($res){echo "o";}
	$sql = "SELECT default_servicestypes FROM `service_industry` WHERE industry_name='".mysql_real_escape_string($post->Industry)."'";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)) {	$def_serv=$row[0]; }
	$sty=explode(',',$def_serv);
	for($i=0; $i<count($sty); $i++){
		$sql="INSERT INTO servicetype VALUES (NULL, 
								'".$user_id."', 
								'".$sty[$i]."', 
								'".mysql_real_escape_string($au)."', $t
												
	)";
	$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());
	}
	/*$ne= mysql_next_result($res); 
	$de=mysql_fetch($res); */
	// store session data
	$_SESSION['user']=$user_id;
	//if($res){echo "o";}
	/*if($res){
		$to=$_GET['email'];
		$subject = "Getcentre Registration";
		$message="Dear ".$_GET['title']." ".$_GET['fname']." ".$_GET['lname'].",<br><br>";
		$message.= 'Thanks for Registering at Getcentre.com<br><br>';	
		$message.= "Best Regards,<br> The GetCentre Team.<br>";    
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From:Getcentre.com\r\n";
			//$message = nl2br($message);
			$a = mail($to, $subject, $message, $headers);
			if($a){echo "o";}                 
	}
	else{echo '1';}		*/			
?>

