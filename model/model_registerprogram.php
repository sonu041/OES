<?php
$prgindx=12348;
include_once('../includes/config.php');
$prgid = $_GET['prgid']-$prgindx;
$studid=$loginfo->getUserId();
/*function sendjsondata(){
	header("Content-Type: application/json; charset=utf-8");
	//echo json_encode( $GLOBALS["data"]);
	endpage();
}*/
/*Check if the user is already registered*/
$check = mysql_query("SELECT sp_id FROM es_std_prog WHERE sp_std_id = '$studid' and sp_prog_id = '$prgid' ") or die(mysql_error());
$checkReg = mysql_num_rows($check);

/* If user is already registered it gives an error */
if ($checkReg != 0) {
 		 $_SESSION['errorval'] = 'Sorry, the user already registerd in this course';
 		 //echo '<script type="text/javascript">alert("Sorry, the username '.$_POST['email'].' is already in use.")</script>';
}
else {
/*Register*/
	$sql="INSERT INTO es_std_prog (sp_std_id, sp_prog_id, sp_status, sp_reg_time) VALUES ('$studid','$prgid','registered','".date("Y-m-d H:i:s")."')";
	if (!mysql_query($sql))
	{
	    die('Error: ' . mysql_error());
	}
 	$_SESSION['errorval'] = 'You have successfully registered in this course';

/*Get program details for mailing*/
	$sql ="select * from es_prog where prog_id=".$prgid;
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

/*Get email id of the loggedin user*/
	$sql2 ="select std_email_id from es_student where std_id=".$studid;
	$res2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

/*Send mail*/
	$to = $row2['std_email_id']; 
	$subject = "You have Successfully Registered for the Course '".$row['prog_name']."' at Engineers Technologies";
	$message = "Dear Fellow Engineer,<br/><br/>
 
Thank you for Registering for the Course '".$row['prog_name']."' at Engineers Technologies. We request you to appear for the Entrance Test by clicking the link 'Take Test' in 'My Program List' well before the applicable end date for the Entrance Test for this particular course. If you qualify in the Entrance Test, we will contact you for enrollment and other formalities .<br/><br/>


Thanks again for your interest in Engineers Technologies.<br/><br/>
 
Warm Regards,<br/>
Engineers Technologies - 'Empowering Intellect to Drive Innovation'<br/><br/>

'Please do not reply to this email'";
	$from = "admin@teamencoder.com";
	sendmail($to,$subject,$message,$replyto="no-reply@teamencoder.com");
}
//sendjsondata();
redirect('../programlist.php');
?>
