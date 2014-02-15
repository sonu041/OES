<?php
include_once('includes/config.php');
$sql = "SELECT std_id, std_name, std_completed FROM es_student WHERE std_email_id = '".$_POST["email"]."' AND std_password = '".$_POST["password"]."'";
$result = mysql_query($sql);
$count = mysql_num_rows($result);
if($count == 0)
{
	//$_SESSION['errorval'] = 'Wrong credential.';
	?>
	<script type="text/javascript" >
		alert("Wrong credential. Press escape.")
		window.parent.clearform("logmein")
	</script>
	<?php
}
else
{
	$row = mysql_fetch_array($result);
	$completed = $row['std_completed']==1 ? "YES" : "NO";
	$loginfo->login($row['std_id'],$row['std_name'], "NO", $completed);
	$redirurl=(isset($_POST['redirectto']) && $_POST['redirectto'] !="") ? $_POST['redirectto'] : "index.php";
	$sql="update es_student set std_lastlogin_time=CURRENT_TIMESTAMP, std_login_ip='".$_SERVER['REMOTE_ADDR']. "', std_isactive='1' where std_email_id = '".$_POST["email"]."'";
	if (!mysql_query($sql))
	{
		?>
		<script type="text/javascript">
		alert("Unknown Error!!");
		</script>
		<?php
	    die('Error: ' . mysql_error());
	}

	?>
	<script type="text/javascript" >
		window.parent.loadagain("<?php echo $redirurl ?>");
	</script>
	<?php
}
//redirect('index.php');
?>
