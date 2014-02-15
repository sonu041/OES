<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<?php
if(isset($_SESSION['errorval']))
{
	$errormsg = $_SESSION['errorval'];
	echo  "<script type=\"text/javascript\">alert('$errormsg')</script>";
	$_SESSION['errorval'] = null;
}
?>
<?php include('includes/middle.php'); ?>
<?php include('includes/content_faq.php'); ?>
<?php include('includes/lower.php'); ?>
 
