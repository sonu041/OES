<?php
$prgindx=12348;
include_once("includes/config.php");
$stdid=$loginfo->getUserId();
if(!$loginfo->isLoggedIn())
{
	redirect("index.php");
}
function mysql_2_php_date($str)
{
	$str=trim($str);
	//'2012-05-14 10:19:30';
	list($day,$time)=explode(" ", $str);
	list($hour,$minute,$second)=explode(":", $time);
	list($year,$month,$day)=explode("-", $day);
	return mktime($hour,$minute,$second,$month,$day,$year);
}
function isallowed($progid,$studid)
{
	//echo $progid.",".$studid;
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
			//echo "gdfc";
			return false;
		}
	}
	else{
		//echo "gdf";
		return false;
	}
	$sql="select max(sp_test_start_time) from es_std_prog where sp_std_id=$studid and sp_prog_id=$progid";// and sp_status='over'";
	$res=mysql_query($sql);
	if($row=mysql_fetch_array($res))
	{
		//var_dump($row);
		if($row[0]==NULL || $row[0]==""){
			//echo "da";
			return true;
		}
		$lasttime=mysql_2_php_date($row[0]);
		//echo $lasttime." \n<br\>";
		if($prog_start_date > $lasttime)
		{
			//echo "asd";
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
		$( "#accordion" ).accordion({
			autoHeight: false,
			navigation: true
		});
		$("a.taketest").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){window.open("question.php?prgid="+$(this).attr("prgid"),"_self")})
		
		$("a.viewporg").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){alert("Not Implemented")})
	});
</script>

<?php include('includes/middle.php'); ?>
<?php //include('includes/content_program.php'); ?>
<div class="demo">
	<div class="prgpad ui-widget-header  ui-corner-all">Programs</div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<div id="accordion">
	<?php
	//echo $stdid;
	$sql="select * from es_prog where prog_deleted=0";
	$res = mysql_query($sql);
	while ($row = mysql_fetch_array($res)) {
	?>
		<h3><a href="#"><?php echo $row['prog_name']; ?></a></h3>
		<div>
			<p>
			<div><?php echo htmlentities ( $row['prog_short_desc']) ?></div>
			<div>
				Started on: <span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row[7]));?></span>,
				Expired on: <span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row[6]));?></span>
			</div>
			<div>
				<a class="viewporg" prgid="<?php echo ($prgindx+$row[0]);?>">View Details</a>
				<?php if( isallowed($row[0],$loginfo->getUserId())) { ?>
				<a class="taketest" prgid="<?php echo ($prgindx+$row[0]);?>">Take Test</a>
				<?php } ?>
			</div>
			</p>
		</div>
	<?php
	}
	mysql_free_result($res);
	?>
	</div>
</div>
<?php include('includes/lower.php'); ?>
