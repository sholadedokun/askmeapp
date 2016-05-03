<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
	require_once 'fun_connect2.php';
	$au=$_GET['user'];
	$ptype=$_GET['type'];
	$service_N=$_GET['serv_no'];
	$t=time();
	$post=json_decode($_GET['post']);
	$res;
	for($i=0; $i<count($post); $i++){
		$pcont=null;
		if(isset($post[$i]->newc)&& isset($post[$i]->newr)){  $pcont=$post[$i]->newc; $a=2; $cont_type='c'; } 
		elseif(isset($post[$i]->newc)|| isset($post[$i]->newr)){
			try{$pcont=$post[$i]->newr;  $cont_type='r';} 
			catch(Exception $e ){$pcont=$post[$i]->newc;  $cont_type='c';} 
			$a=1;
		}
		else{continue;}
		for($x=0; $x<$a; $x++){
			
			if($x==1){$pcont=$post[$i]->newr;  $cont_type='r';}
			$sql="INSERT INTO post VALUES (NULL, 
										'".mysql_real_escape_string($service_N)."',
										'".mysql_real_escape_string($post[$i]->parent)."', 
										'".mysql_real_escape_string($pcont)."', 
										'".$ptype."', 
										'.$cont_type.',
										'".mysql_real_escape_string($au)."', 
										'Publish', 
										$t				
			)";
			$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());
			if($res){echo "o";}
		}
	}
	//$user_id = mysql_insert_id();
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

