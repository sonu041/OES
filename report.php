<?php include('includes/config.php');
if(!$loginfo->isLoggedIn())
{
	redirect("index.php");
}
$fylindx=1645;
$prgindx=12348;
$spids="";
$prids="";
$prspmap=Array();
$programs=Array();
if(isset($_GET['spid']) && $_GET['spid']!=''){
	$sql="select sp_id, prog_name, sp_test_start_time, sp_test_end_time, sp_correctly_answered, sp_is_qualified, prog_id from es_std_prog, es_prog where sp_prog_id=prog_id and sp_std_id='".$loginfo->getUserId()."' and sp_id='" . $_GET['spid'] . "' and sp_status = 'over'";
	$result = mysql_query($sql);
	//echo $sql;
	//$count = mysql_num_rows($result);
	while($row=mysql_fetch_row($result))
	{
		if($row[5]){
			$prids .= $row[6].",";
			$prspmap[$row[6]]=$row[0];
		}
		$spids .= $row[0].",";
		$programs[] = $row;
	}
}

if(count($programs)==0){
	$sql="select sp_id, prog_name, sp_test_start_time, sp_test_end_time, sp_correctly_answered, sp_is_qualified, prog_id from es_std_prog, es_prog where sp_prog_id=prog_id and sp_std_id='".$loginfo->getUserId()."' and sp_status = 'over'";
	$result = mysql_query($sql);
	//$count = mysql_num_rows($result);
	while($row=mysql_fetch_row($result))
	{
		if($row[5]){
			$prids .= $row[6].",";
			$prspmap[$row[6]]=$row[0];
		}
		$spids .= $row[0].",";
		$programs[] = $row;
	}
}
$spids=trim($spids, $charlist = ",");
$prids=trim($prids, $charlist = ",");
//echo $spids;

$modRes=Array();

$sql = "SELECT mr_sp_id, mod_name, mr_tot_qsn, mr_correct, mr_worng, mr_is_qua FROM es_mod_result, es_module where mod_id = mr_mod_id and mr_sp_id in ($spids)";
$result = mysql_query($sql);
while($row = mysql_fetch_row($result))
{
	if(!isset($modRes[$row[0]])){
		$modRes[$row[0]] = Array();
	}
	
	$modRes[$row[0]][] = $row;
}
//print_r($modRes);
$progFiles=Array();
$sql = "select pf_id, pf_prog_id from es_prog_file where pf_prog_id in ($prids)";
$result = mysql_query($sql);
if($result)
while($row = mysql_fetch_array($result)){
	if(!isset($progFiles[$row[1]+$prgindx]))
		$progFiles[$row[1]+$prgindx] = Array();
	// prgidx=[fylindex, spid]
	$progFiles[$row[1]+$prgindx][] = [$row[0]+$fylindx,$prspmap[$row[1]]];
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<?php
if(isset($_SESSION['errorval']))
{
	$errormsg = $_SESSION['errorval'];
	//echo  "<script type=\"text/javascript\">alert('$errormsg')</script>";
	$_SESSION['errorval'] = null;
}
?>
<?php include('includes/middle.php'); ?>
<div class="demo" id="report-container">

<?php
foreach($programs as $prog){
	echo "<h3><a href=\"#\"><table border=0 width=\"90%\" align=\"center\"><tr><td align=\"left\">".$prog[1]."</td><td align=\"right\">";
	echo $prog[5]? "<font color='blue'><b>Pass</b></font>":"<font color='red'><b>Failed</b></font>";
	echo "</td></tr></table></a></h3>";
	echo "<div>";
	echo "<table border=0 width=\"90%\" align=\"center\"><tr>";
		echo "<td>From: ".$prog[2]."</td>";
		echo "<td>To: ".$prog[3]."</td>";
		//echo "<td>".$prog[4]."</td>";
		echo "<td>Status: ";
		echo $prog[5] ? "<font color='blue'><b>Pass</b></font>" : "<font color='red'><b>Failed</b></font>";
		echo "</td>";
	echo "</tr></table>";
	echo "<br /><br />";
	echo "<table border=0 align=\"center\" width=\"90%\">";
	foreach($modRes[$prog[0]] as $p){
		echo "<tr>";
			echo "<td>name:<b>".$p[1]."</b></td>";
			echo "<td>Total: ".$p[2]."</td>";
			echo "<td>Correct: ".$p[3]."</td>";
			echo "<td>Wrong: ".$p[4]."</td>";
			echo "<td>Status: ";
			echo $p[5]?"<font color='blue'><b>Pass</b></font>":"<font color='red'><b>Failed</b></font>";
			echo "</td>";
	}
	if(isset($progFiles[$prog[6]+$prgindx])){
		echo "<tr>";
			echo "<td colspan=5 align='center'><br /> <br /><a class='examfile' file='".($prog[6]+$prgindx)."' style='cursor:pointer'>Click here to Download files and upload your response</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "</div>";
}
?>

</div>
<div id="file-dialog" title="Files">
	<div>Download all files. You will find some instruction there.</div>
	<div id="req-file"></div>
	<br />
	<hr />
	<div>
		You can upload only one file. Every time you upload a file, Its replace the previous one and its irreversible. If you have multiple file, Please make a zip file and upload it.<br />
		We expect that you will upload PDF or ZIP file. 
	</div>
	<br />
	<div align="center">
		<form target="upload-target" id="upload-response" action="model/model_uploadrespons.php" enctype="multipart/form-data" method="post">
			<input type="file" name="file" />
			<input type="hidden" name="spid" id="file_sp_id" /><br />
			<input type="submit" id="submituploadres" value="Upload" />
		</form>
		<iframe name="upload-target"></iframe>
	</div>
</div>
<script type="text/javascript" >
//prog = <?php //echo json_encode($programs) ?>;
$(function() {
		$("iframe").hide()
		$("#submituploadres").button()
		$( "#report-container" ).accordion({
			autoHeight: false,
			navigation: true,
			collapsible: true
		});
		
		$("#file-dialog").dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
		})
		$(".examfile").click(function(){
			try{
				clearform("upload-response")
				regfile=$("#req-file")

				regfile.empty()

				fylid=$(this).attr("file")
				for(i in files[fylid]){
					
					lnk=$("<a></a>")
					.attr("href","file.php?fylid="+files[fylid][i][0])
					.text("File "+i)
					.css("color","#0000ff")
					
					div=$("<div></div>")
					.attr("align","center")
					.css("font-weight","bold")
					.append(lnk)

					regfile
					.append(div)
				}
				$("#file_sp_id").attr("value",files[fylid][0][1])
				$("#file-dialog").dialog("open")
			}catch(e){
				alert(e)
			}
		})
		.button()
		
	});
//modRes = <?php //echo json_encode($modRes) ?>;
files = <?php echo json_encode($progFiles) ?>;
</script>
<?php include('includes/lower.php'); ?>
 
