<?php
include_once('../includes/config.php');
//$d_arr = explode("/", $_POST[dob]);
//$dob = $d_arr[2]."-".$d_arr[0]."-".$d_arr[1];
/*foreach($_POST as $k=>$v){
	$_POST[$k] = xss_decode($_POST[$k]);
}*/
$dob = xss_decode($_POST['dob']);
$name = xss_decode($_POST['name']);
$contact = xss_decode($_POST['contact']);
$address = xss_decode($_POST['address']);
$city = xss_decode($_POST['city']);
$state = xss_decode($_POST['state']);
$country = xss_decode($_POST['country']);
$pin = xss_decode($_POST['pin']);

$sql="UPDATE es_student SET std_name='$name', std_contact_no='$contact', std_address='$address', std_city='$city', std_pincode='$pin', std_dob='$dob', std_state='$state', std_country='$country', std_completed=1 WHERE std_id=$_SESSION[userId]";
if (!mysql_query($sql))
{
	?>
	
	<script type="text/javascript" >
		alert('Unknown Error')
	</script>
	<?php
   	die('Error: ' . mysql_error());
	//$_SESSION['errorval'] = 'Error: ' . mysql_error();
}
else
{
	//echo ("<script type=\"text/javascript\">alert(\"Your password has been successfully changed.\");</script>");
	//$_SESSION['errorval'] = 'Your profile has been successfully updated.';
	?>
	<script type="text/javascript" >
		alert('Your profile has been successfully updated.')
		window.parent.loadagain('../profile.php')
	</script>
	<?php
}
//redirect('../profile.php');
?>
