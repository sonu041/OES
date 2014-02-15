<?php
$rootpath="../";
$fylindx=1645;
$imgindx=1234;
$prgindx=12348;
//echo "here";
//die;
include('includes/config.php');

if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}
//print_r($_FILES);die;
if(isset($_FILES["newfile"]))
{	
	if($_FILES["newfile"]["error"] > 0)
	{
		//echo $_FILES["newfile"]["error"];die;
		if($_FILES["newfile"]["error"]==1 || $_FILES["newfile"]["error"]==2)
			$_SESSION['fylmngerror']="Error: File Size exceeded";
		else 
			$_SESSION['fylmngerror']="Error in uploading";
		redirect("fylmng.php?progid=".$_GET['progid']);
	}
	$name="newfile";
	$openedfile = file_get_contents($_FILES[$name]["tmp_name"]);
    $filehash = md5_file($_FILES[$name]["tmp_name"]);
    $ext = pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
    $contents = "0x".bin2hex ($openedfile);
    $type=$_FILES[$name]["type"];
    $size=$_FILES[$name]["size"];
    $sql="insert into es_prog_file (pf_prog_id, pf_type, pf_size,pf_ext, pf_file) values('". ($_GET['progid']-$prgindx)."','$type','$size', '$ext',$contents)";
    //echo $sql;die;
    mysql_query($sql);
    $er=mysql_error();
    if($er!="") 
		$_SESSION['qsnmngerror']="Unknown Error";//$er;//." ".$sql;
	else
    	$_SESSION['fylmngerror']="Uploaded successfully";
    //$size = getimagesize ($_FILES[$name]["tmp_name"]);
	redirect("fylmng.php?progid=".$_GET['progid']);
}
if(!isset($_GET["progid"]) && $_GET["progid"]=="")
{
	//die("1");
	redirect("prgmng.php");
}
$sql="SELECT * FROM es_prog WHERE prog_id = '". ($_GET['progid']-$prgindx)."' and prog_deleted=0";
$res = mysql_query($sql);

if($c_sub = mysql_fetch_array($res));
else {
		//die("2");
		//echo $sql; die;
		redirect("prgmng.php");
}



if(isset($_GET['delid']) && $_GET['delid'] > $fylindx)
{
	//print_r($_GET);die;
	$sql="delete from es_prog_file where pf_id=".($_GET['delid'] - $fylindx);
	mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	if($er!="") 
		$_SESSION['qsnmngerror']=$er;
	redirect("fylmng.php?progid=".$_GET["prgid"]);
}
$error="";
if(isset($_SESSION['fylmngerror']))
{
	$error=$_SESSION['fylmngerror'];
	unset($_SESSION['fylmngerror']);
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<style type="text/css">
.tstpad{ padding:2px;}
img.fylopt{height: 20px; width: 20px;}
.brdrtable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
label.error {  color: red; padding-right: 50px; padding-bottom: 50px; vertical-align: top; }
p { clear: both; }
#demo-frame > div.demo { padding: 10px !important; };

</style>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<?php include('includes/middle.php'); ?>
<div id="testContent" class="tstpad" subid="<?php echo $c_sub[0]+$fylindx ?>">
	<div class="fylpad ui-widget-header  ui-corner-all"><?php echo $c_sub[1] ?></div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<div class="ui-corner-all ui-widget">
	<table width="100%" class="brdrtable">
	<tr><td colspan="10" class="brdrtable">
	<div><b>Program :</b> <?php echo $c_sub[1] ?></div>
	<div><b>Description:</b></div>
	<div><?php echo $c_sub[2] ?></div>
	</td></tr>
	<tr><td colspan="10" class="brdrtable"><a href="#" id="addnewfyl">Add file</a><span style="float:right"><a href="prgmng.php" >Back to program Managment</a></span></td></tr>
	<?php
	$sql="SELECT * FROM es_prog_file WHERE pf_prog_id = '$c_sub[0]'";
	//echo $sql;
	$res = mysql_query($sql);
	if($row = mysql_fetch_array($res)) {
		$i=2;
	?>
		<tr>
			<td class="brdrtable"><a href="../file.php?fylid=<?php echo ($row[0]+$fylindx) ?>">Download</a></td>
			<td class="brdrtable"><a class="delfyl" delid="<?php echo ($row[0]+$fylindx) ?>" href="#">Delete</a></td>
		</tr>
		<?php
		}else{
			echo "<tr><td align=\"center\">No file found for this program</td></tr>";
		}
		while($row = mysql_fetch_array($res)) {
		$i=2;
	?>
		<tr>
			<td class="brdrtable"><a href="../file.php?fylid=<?php echo ($row[0]+$fylindx) ?>">Download</a></td>
			<td class="brdrtable"><a class="delfyl" delid="<?php echo ($row[0]+$fylindx) ?>" href="#">Delete</a></td>
		</tr>
	<?php
		}
	mysql_free_result($res);

	?>
	</table>
	</div>
<script type="text/javascript">
		$(function(){
			$("#addnewfyl").click(function(){
				ip=p=$("<p></p>")
				.append('<label class="login" for="newfile">File</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="file" name="newfile" id="newfile"/>')
				//.append('<input type="hidden" name="adfile" vale="newfile"/>')
				cont=$("<div></div>")
				.css("margin-bottom","10px")
				.append(ip)
				form=$("<form></form>")
				.attr("enctype","multipart/form-data")
				.attr("method","POST")
				.append(cont)
				
				sbtn=$("<input/>")
				.attr("type","submit")
				.attr("name","addqsn")
				.val("Add")
				.button()	
				clbtn=$("<input/>")
				.attr("type","button")
				.val("Cancel")
				.button()
				.click(function(){
					diaclose();
					return false;
				})

				
				form
				.append(sbtn)
				.append(clbtn)
				try{
					diaopen("Add New File",form)
				}catch(e){alert(e)}
				return false;
			})
			$(".delfyl").click(function(){
				if(confirm("R you sure?")){
					//alert(progs[$(this).attr("delid")][0]);
					window.open("fylmng.php?progid=<?php echo $_GET["progid"]?>&delid="+$(this).attr("delid"),"_self")
				}
				return false;
			})
		})
		//-->
</script>
</div>
<?php //include('includes/content_home.php'); ?>
<?php include('includes/lower.php'); ?>
