<?php
$rootpath="../";
include('includes/config.php');
$tstindx=15632; 
if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}

if(isset($_GET['delid']) && $_GET['delid'] > $tstindx)
{
	$sql="select deltest(\"".($_GET['delid'] - $tstindx)."\")";
	
	mysql_query($sql);
	$er=mysql_error();
	if($er!="") 
		$_SESSION['tstmngerror']=$er;
	//else 	$_SESSION['submngerror']=$sql;
	redirect("tstmng.php");
}
$error="";
if(isset($_SESSION['tstmngerror']))
{
	$error=$_SESSION['tstmngerror'];
	unset($_SESSION['tstmngerror']);
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<style type="text/css">
.tstpad{ padding:2px;}
img.qsnopt{height: 20px; width: 20px;}
.brdrtable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
label.error {  color: red; padding-right: 50px; padding-bottom: 50px; vertical-align: top; }
p { clear: both; }
#demo-frame > div.demo { padding: 10px !important; };

</style>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<?php include('includes/middle.php'); ?>
<div id="testContent" class="tstpad insidecontent">
	<div class="qsnpad ui-widget-header">Test Manager</div>
	<div class="ui-corner-all ui-widget">
	<center><a href="createtest.php">Add New Test</a></center>
		
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<table class="brdrtable" width="100%"> 
	<?php
		$sql="select mod_test_id, count(mod_id) from es_module group by mod_test_id";		
		$res = mysql_query($sql);
		$test=NULL;
		while ($row = mysql_fetch_array($res)) {
			$test[$row[0]]=$row[1];
		}
		$sql="select prog_test_id, count(prog_id) from es_prog group by prog_test_id";		
		$res = mysql_query($sql);
		$prog=NULL;
		while ($row = mysql_fetch_array($res)) {
			$prog[$row[0]]=$row[1];
		}
		//print_r($subject);*/
		$sql="SELECT * FROM es_v_test";
		$res = mysql_query($sql);
		if($row = mysql_fetch_array($res)) {
	?>
		<tr subid=-11>
			<th class="brdrtable" width="100px"><strong>Test Name</strong></th>
			<th class="brdrtable" width="60px"><strong>Test Code</strong></th>
			<th class="brdrtable"><strong>Description</strong></th>			
			<th class="brdrtable" width="60px"><strong>Duration (in minutes)</strong></th>
			<th class="brdrtable" width="70px"><strong>Number of question</strong></th>
			<th class="brdrtable" width="60px"><strong>Number of modules</strong></th>
			<th class="brdrtable" width="100px"><strong>Number of Program</strong></th>			
			<th class="brdrtable" width="80px"><strong>Delete</strong></th>
		</tr>
		<tr subid="<?php echo $row[0]+$tstindx;?>">
			<td class="brdrtable"><?php echo $row["test_name"];?></td>
			<td class="brdrtable"><?php echo $row["test_code"];?></td>
			<td class="brdrtable"><?php echo $row["test_description"];?></td>
			<td class="brdrtable"><?php echo $row["test_duration"];?></td>
			<td class="brdrtable"><?php echo $row["test_totalquestion"];?></td>
			<td class="brdrtable"><?php echo isset($test[$row[0]])?$test[$row[0]]:0;?></td>		
			<td class="brdrtable"><?php echo isset($prog[$row[0]])?$prog[$row[0]]:0;?></td>		
			<td class="brdrtable"><!--<a href="question.php?subid=<?php echo ($row[0]+$tstindx);?>">Manage</a>-->
			<?php
			if(!isset($subject[$row[0]]))
				echo "<br><a class=\"deltest\" delid=\"".($row[0]+$tstindx)."\" href=\"#\" title=\"Delete this Subject\">Delete</a>";
			?>
			</td>
		</tr>
		<?php
		}else{
			echo "<tr><td align=\"center\">No Test Found</td></tr>";
		}
		
		while ($row = mysql_fetch_array($res)) {
	?>
		<tr subid="<?php echo $row[0]+$tstindx;?>">
			<td class="brdrtable"><?php echo $row["test_name"];?></td>
			<td class="brdrtable"><?php echo $row["test_code"];?></td>
			<td class="brdrtable"><?php echo $row["test_description"];?></td>
			<td class="brdrtable"><?php echo $row["test_duration"];?></td>
			<td class="brdrtable"><?php echo $row["test_totalquestion"];?></td>
			<td class="brdrtable"><?php echo isset($test[$row[0]])?$test[$row[0]]:0;?></td>	
			<td class="brdrtable"><?php echo isset($prog[$row[0]])?$prog[$row[0]]:0;?></td>			
			<td class="brdrtable"><!--<a href="question.php?subid=<?php echo ($row[0]+$tstindx);?>">Manage</a>-->
			<?php
			if(!isset($subject[$row[0]]))
				echo "<br><a class=\"deltest\" delid=\"".($row[0]+$tstindx)."\" href=\"#\" title=\"Delete this Subject\">Delete</a>";
			?>
			</td>
		</tr>
		<?php
		}
		mysql_free_result($res);
		?>

	</table>
	<script type="text/javascript">
	//<!--
	$(function(){
		$(".deltest").click(function(){
			if(confirm("Are Sure?\nThis is irrevesible."))
			{
				window.open("?delid="+$(this).attr("delid"),"_self")
			}
			return false;
		})
	})
	//-->
</script>
	</div>
</div>
<?php include('includes/lower.php'); ?>
