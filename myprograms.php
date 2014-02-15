<?php
$prgindx=12348;
$fylindx=1645;
$logedIn = 0;
include_once("includes/config.php");
$stdid=$loginfo->getUserId();
if(!$loginfo->isLoggedIn())
{
	redirect("index.php");
}
function mysql_2_php_date($str)
{
	$str=trim($str);
	list($day,$time)=explode(" ", $str);
	list($hour,$minute,$second)=explode(":", $time);
	list($year,$month,$day)=explode("-", $day);
	return mktime($hour,$minute,$second,$month,$day,$year);
}
function isallowed($progid,$studid)
{
	$sql="select prog_start_date, prog_expire_date from es_prog where prog_id=$progid";
	$res=mysql_query($sql);
	$prog_start_date=0;
	$prog_exp_date=0;
	if($row=mysql_fetch_array($res))
	{
		$prog_start_date=mysql_2_php_date($row[0]);
		$prog_exp_date=mysql_2_php_date($row[1]);
		if($prog_start_date > time() || $prog_exp_date < time())
		{
			return false;
		}
	}
	else{
		return false;
	}
	$sql="select max(sp_test_start_time) from es_std_prog where sp_std_id=$studid and sp_prog_id=$progid";// and sp_status='over'";
	$res=mysql_query($sql);
	if($row=mysql_fetch_array($res))
	{
		if($row[0]==NULL || $row[0]==""){
			return true;
		}
		$lasttime=mysql_2_php_date($row[0]);
		if($prog_start_date > $lasttime)
		{
			return true;
		}
		return false;
	}
	return true;
}

$error="";
if(isset($_SESSION['prgerror']))
{
	$error=$_SESSION['prgerror'];
	unset($_SESSION['prgerror']);
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<script>
	$(function() {
		$("a.taketest").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){window.open("question.php?spid="+$(this).attr("spid"),"_self")})
		
		$("a.report").css("cursor","pointer")
		.css("text-decoration","underline")
		//.click(function(){window.open("question.php?prgid="+$(this).attr("prgid"),"_self")})
		.click(function(){alert('Report will be generated');})
	});
</script>

<?php include('includes/middle.php'); ?>
<?php //include('includes/content_program.php'); ?>
<div class="demo">
	<div class="prgpad ui-widget-header">&nbsp;My Program list</div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>

<?/*This will show the users registered programs.*/?>
	<div id="accordion" class="insidecontent">
	<?php
	//$sql="select * from es_prog ep,es_std_prog esp where ep.prog_id = esp.sp_prog_id and esp.sp_std_id =".$loginfo->getUserId()." and prog_deleted=0 order by sp_status desc";
	#$sql="select * from es_prog where prog_deleted=0";
	$sql ="select prog_id, prog_name, prog_short_desc, prog_expire_date, prog_start_date, sp_id, sp_status, prog_test_isrequired, sp_is_qualified from es_prog, es_std_prog ";
	$sql.="where sp_prog_id=prog_id and prog_deleted=0 and sp_std_id=$stdid order by sp_status desc";
	//echo $sql;
	$prids = "";
	$prspmap = Array();
	$progs = Array();
	$res = mysql_query($sql);
	while ($row = mysql_fetch_array($res)) {
		if(($row['prog_test_isrequired'] && $row['sp_is_qualified']) || (!$row['prog_test_isrequired'])){
			$prids .= $row['prog_id'].",";
			$prspmap[$row['prog_id']]=$row['sp_id'];
		}
		$progs[]=$row;
	}
	mysql_free_result($res);
	$prids = trim($prids, $charlist = ",");
	$progFiles=Array();
	$sql = "select pf_id, pf_prog_id from es_prog_file where pf_prog_id in ($prids)";
	//echo $sql;
	$result = mysql_query($sql);
	if($result)
		while($row = mysql_fetch_array($result)){
			if(!isset($progFiles[$row[1]+$prgindx]))
				$progFiles[$row[1]+$prgindx] = Array();
			// prgidx=[fylindex, spid]
			$progFiles[$row[1]+$prgindx][] = Array($row[0]+$fylindx,$prspmap[$row[1]]);
		}
	if(count($progs)<=0){
	?>
	<h3 style="font-size:18px; color:rgb(0,0,255); font-family: Arial; letter-spacing:1px; ">No Program Found</h3>
	<?php
	}
	foreach($progs as $row){
	?>
		<h3 style="font-size:18px; color:rgb(0,0,255); font-family: Arial; letter-spacing:1px; "><a style="text-decoration: none;" href="programdetails.php?progid=<?php echo ($row['prog_id']+$prgindx) ?>"><?php echo $row['prog_name']; ?></a></h3>
		<div>
			<p>
			<div><?php echo htmlentities ( $row['prog_short_desc']) ?></div>
			<div>
				Start Date: <span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row['prog_start_date']));?></span>,
				Expire Date: <span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row['prog_expire_date']));?></span>
			</div>
			<div>
				<?php //if( isallowed($row[0],$loginfo->getUserId()) && $row['prog_test_isrequired']) { ?>
				<?php if( $row['prog_test_isrequired']) { 
							if($row['sp_status'] == 'registered'){
							if(strtotime($row['prog_expire_date']) >= time()){
				?>
				<a class="taketest" spid="<?php echo ($row['sp_id']);?>">Take Test</a>
				<?php 	} else{ ?>
				<b><font color="red"><blink><a title="">Expired</a></blink></font></b>
				<?php }
				      } else if ($row['sp_status'] == 'over'){?> 
				<b><font color="blue"><blink>Exam Over</blink></font>
				<?php 	} else{ ?>
				<b><font color="red"><blink><a title="It may be a case when you have close the browser or cancel the test when it was running">Exam In progress</a></blink></font></b>
				<?php		}
						} else { ?>
						<b><font color="blue"><a>No Exam required for this program</a></font></b>
				<?php
						}
				
				if(0 && (isset($progFiles[$row['prog_id']+$prgindx])) && (($row['prog_test_isrequired'] && $row['sp_is_qualified']) || (!$row['prog_test_isrequired'])))
					echo "<a class='examfile' file='".($row['prog_id']+$prgindx)."' style='cursor:pointer'>Click here to Download files and upload your response</a>"
				?>
			</div>
			</p><hr>
		</div>
	<?php
	}
	?>
	</div>
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
				//alert(fylid)
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
		.css("color","#555555")
		.css("text-decoration","underline")
		//.button()
		
	});
//modRes = <?php //echo json_encode($modRes) ?>;
files = <?php echo json_encode($progFiles) ?>;
</script>
<?php include('includes/lower.php'); ?>
