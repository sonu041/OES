<?php
include_once('../includes/config.php');
if(!$loginfo->isLoggedIn()){
	?>
	<script type="text/javascript" >
		alert("You are not logged in. Please login first.")
		window.parent.loadagain("../index.php")
	</script>
	<?php
}

if(isset($_POST['spid']) && $_POST['spid'] > 0 && isset($_FILES["file"]))
{	
	$sql="select sp_id, prog_id, prog_test_isrequired, sp_status from es_prog, es_std_prog where sp_prog_id=prog_id and sp_id=".$_POST['spid'];
	$result = mysql_query($sql);
	$er=mysql_error();
	if($er!=""){
		?>
		<script type="text/javascript" >
			alert("Unknown error!!");
		</script>
		<?php
		die();
	}
	$row = mysql_fetch_array($result);
	if(!$row){
		?>
		<script type="text/javascript" >
			alert("Unknown error!!");
		</script>
		<?php
		die();
	}
	if($row['prog_test_isrequired'] && $row["sp_status"]!='over') {
		?>
		<script type="text/javascript" >
			alert("Unknown error!!");
		</script>
		<?php
		die();
	}
	if($_FILES["file"]["error"] > 0)
	{
		//echo $_FILES["newfile"]["error"];die;
		if($_FILES["file"]["error"]==1 || $_FILES["file"]["error"]==2){
			?>
			<script type="text/javascript" >
				alert("Error: File Size exceeded");
			</script>
			<?php
			die();
			//$_SESSION['fylmngerror']="Error: File Size exceeded";
		}else{
			?>
			<script type="text/javascript" >
				alert("Error in uploading!!");
			</script>
			<?php
			die();
			//$_SESSION['fylmngerror']="Error in uploading";
		} 
			
		//redirect("fylmng.php?progid=".$_GET['progid']);
	}
	$name="file";
	$openedfile = file_get_contents($_FILES[$name]["tmp_name"]);
	$filehash = md5_file($_FILES[$name]["tmp_name"]);
	$ext = pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
	$contents = "0x".bin2hex ($openedfile);
	$type=$_FILES[$name]["type"];
	$size=$_FILES[$name]["size"];
	$sql="replace into es_std_response (sr_sp_id, sr_type, sr_size, sr_ext, sr_file) values('". ($_POST['spid'])."','$type','$size', '$ext',$contents)";
	//echo $sql;die;
	mysql_query($sql);
	$er=mysql_error();
	if($er!=""){
		?>
		<script type="text/javascript" >
			alert("Unknown Error!!");
		</script>
		<?php
		die();
		//$_SESSION['fylmngerror']="Error: File Size exceeded";
		//S$_SESSION['qsnmngerror']=$er;//." ".$sql;
	}
	else{
		?>
		<script type="text/javascript" >
			alert("Uploaded successfully.");
			window.parent.$("#file-dialog").dialog("close")
		</script>
		<?php
		die();
		//$_SESSION['fylmngerror']="Error: File Size exceeded";
		//S$_SESSION['qsnmngerror']=$er;//." ".$sql;
	}
//		$_SESSION['fylmngerror']="Uploaded successfully";
	//$size = getimagesize ($_FILES[$name]["tmp_name"]);
	//redirect("fylmng.php?progid=".$_GET['progid']);
}
//print_r($_POST);
//print_r($_FILES);
?>
<script type="text/javascript" >
	alert("Error.");
</script>