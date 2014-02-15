<?php
include_once('../includes/config.php');

//This makes sure they did not leave any fields blank
if (!$_POST['name'] | !$_POST['email']) {
		?>
	<script type="text/javascript" >
		alert('You did not complete all of the required fields.')
	</script>
		<?php

 		die('You did not complete all of the required fields.');

 	}
//Check for email id
$check = mysql_query("SELECT std_email_id FROM es_student WHERE std_email_id = '$_POST[email]'") or die(mysql_error());
$checkPass = mysql_num_rows($check);



//if the email exists it gives an error

if ($checkPass != 0) {

 		 //$_SESSION['errorval'] = 'Sorry, the username '.$_POST['email'].' is already in use.';
 		 ?>
 		 <script type="text/javascript">
 		 window.parent.seterror("register-email"); 
 		 alert("Sorry, the email id already taken <?php echo $_POST['email'] ?> is already in use.")
 		 </script>
 		 <?php
		 die();
}
else {
//Register

	$password = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,10);
	$sql="INSERT INTO es_student (std_name, std_password, std_email_id, std_contact_no, std_isactive) VALUES ('$_POST[name]','$password','$_POST[email]','$_POST[contact]', '0')";

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
	$subject = "First Time Registration Password from EngineersTechnologies";
	$message = "Dear Fellow Engineer,<br/><br/>
 
Warm Greetings from Engineers Technologies !!!<br/><br/>
 
Please use  'First Time Registration Password' provided below with you email as LOGIN ID  for registration into any of the course of your choice listed in our 'Program' Menu,in the Engineers Technologies website.<br/><br/>
 
Your 'First Time Registration Password' is <b>".$password."</b><br/><br/>
 
 
Thanks and Regards<br/>
 
Engineers Technologies - 'Empowering Intellect to Drive Innovation'";
	$from = "admin@teamencoder.com";
	sendmail($to,$subject,$message,$replyto="no-reply@teamencoder.com");
	$redirurl=(isset($_POST['redirectto']) && $_POST['redirectto'] !="") ? $_POST['redirectto'] : "index.php";
}
?>
	<script type="text/javascript" >
		//window.parent.loadagain("<?php echo $redirurl ?>");
		alert("Your password has been sent to your email.");
		window.parent.$( "#register-dialog-form" ).dialog('close')
	</script>

