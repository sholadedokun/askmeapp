<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);	
	require_once 'fun_connect2.php';
	session_start();
	echo($_GET['report']);
	$pic_upload="";$feat="";
	$curtime=time(); 
	$directory="../images/reportimages";$z=1;
	for($x=1; $x<10; $x++){
		$pan="pic".$x;
		if(isset($_FILES["$pan"])){ 
			$tmp_name1 = $_FILES["$pan"]['tmp_name'];
			if (file_exists($tmp_name1)){ 
				if(($_FILES["$pan"]["size"]<=2048000)&&($_FILES["$pan"]["size"]>=10240)){
				$name = $_FILES["$pan"]['name'];
				$exp=explode(".",$name);
				$newname=$curtime."$x.".$exp[count($exp)-1];					
					if(is_uploaded_file($tmp_name1)){
						$move = move_uploaded_file($tmp_name1,"$directory/$newname");
						chmod ("$directory/$newname",0777);
						if($z>1){$pic_upload= $pic_upload.",".$newname;}
						else{$pic_upload=$newname;}
						${'name'.$x}=$newname;$z++;
						$prof_pic= $name1;
					}
				}
			}
		}
	}
	$t=time();
	$report=json_decode($_GET['report']);
	echo print_r($report);
	$agent=$report->Agent;
	$user=$_GET['userId'];
	$location=$report->Location;
	$state=$report->State;
	$l_government=$report->Local_government;
	$rtype=$report->Type;
	$description=$report->Description;
	$sql="INSERT INTO report VALUES (NULL, 
													'".mysql_real_escape_string($rtype)."', 
													'".mysql_real_escape_string($user)."', 
													'".mysql_real_escape_string($agent)."', 
													'".mysql_real_escape_string($location)."', 
													'".mysql_real_escape_string($state)."', 
													'".mysql_real_escape_string($l_government)."', 
													'".mysql_real_escape_string($description)."', 
													'',
													'', 
													'',
													$t				
	)";
	$res=mysql_query($sql)or die ("Error : could not insert values" . mysql_error());	
	$user_id = mysql_insert_id();
	/*$ne= mysql_next_result($res); 
	$de=mysql_fetch($res); */
	// store session data
	$_SESSION['user']=$user_id;
	if($res){echo "o";}
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

