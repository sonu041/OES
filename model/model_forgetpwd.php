<?php
include_once('../includes/config.php');

//This makes sure they did not leave any fields blank
if (!$_POST['email']) {

 		die('You did not complete all of the required fields.');

 	}
//Check for email id
$check = mysql_query("SELECT std_email_id FROM es_student WHERE std_email_id = '$_POST[email]'") or die(mysql_error());
$checkPass = mysql_num_rows($check);

//if the email exists it gives an error
if ($checkPass == 0) {

 		 ?>
 		 <script type="text/javascript">
 		 window.parent.seterror("forgetpwd-email"); 
 		 alert("<?php echo $_POST['email'] ?> is not in our database. Press escape.")
 		 </script>
 		 <?php
}
else {
//Register

	$password = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,10);
	$sql = "UPDATE es_student SET std_password ='$password' WHERE std_email_id='$_POST[email]'";

	if (!mysql_query($sql))
	{
	?>
		<script type="text/javascript">
		alert("Unknown Error!!");
		</script>
	<?php
	    die('Error: ' . mysql_error());
	}

	/*Send mail*/
	$to = $_POST['email'];
	$subject = "Password Regenerated : Engineers Technologies";
	$message = "Dear Fellow Engineer,<br/><br/>
 
As per your request, your password is regenerated please use 'Regenerated Password' provided below with you email ID as LOGIN ID to login into the Engineers Technologies website.<br/><br/>
 
Your 'Regenerated Password' is <b>".$password. "</b><br/><br/>
 
Do not Forget to change your password after login into Engineers Technologies website<br/><br/>
 
Thanks and Regards<br/>
Engineers Technologies - 'Empowering Intellect to Drive Innovation'";

	$from = "admin@teamencoder.com";
	sendmail($to,$subject,$message,$replyto="no-reply@teamencoder.com");
	$redirurl=(isset($_POST['redirectto']) && $_POST['redirectto'] !="") ? $_POST['redirectto'] : "index.php";
}
?>
<script type="text/javascript" >
		alert("Your password has been sent to your email.");
		window.parent.$( "#forgetpwd-dialog-form" ).dialog('close')
	</script>
