<?php
include_once('../includes/config.php');
$sql="UPDATE es_student SET std_password='$_POST[newpass]' WHERE std_id=$_SESSION[userId]";
if (!mysql_query($sql))
{
   	//die('Error: ' . mysql_error());
	$_SESSION['errorval'] = 'Error: ' . mysql_error();
}
else
{
	//echo ("<script type=\"text/javascript\">alert(\"Your password has been successfully changed.\");</script>");
	$_SESSION['errorval'] = 'Your password has been successfully changed.';
}
redirect('../index.php');
?>
