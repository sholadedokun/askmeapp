<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
	require_once 'fun_connect2.php';
	session_start();
	$npass=md5($_GET['npass']);	
	$sql="Update registered_user SET userPassword = '$npass'";
	$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());	
		
	if($res){
		$to=$_GET['email'];
		$subject = "AskMe.ng Password Update";
		//$message="Dear ".$_GET['title']." ".$_GET['fname']." ".$_GET['lname'].",<br><br>";
		$message.= 'Your Password has just been updated on the GETCentre APP, if you didn\'t authorise this action please contact AskMe Admin admin@askme.com<br><br>';	
		$message.= "Best Regards,<br> The AskMe Team.<br>";    
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= "From:AskMe.com\r\n";
			//$message = nl2br($message);
			$a = mail($to, $subject, $message, $headers);
			if($a){echo "o";}                 
	}
	else{echo '1';}					
?>

