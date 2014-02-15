<?php
$prgindx=12348;
$logedIn = 0;
include_once("includes/config.php");
$stdid=$loginfo->getUserId();
if(!$loginfo->isLoggedIn())
	{
		$logedIn = 1;  
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

$registered=Array();

if($loginfo->isLoggedIn())
{
	$query = "select sp_prog_id from es_std_prog where sp_std_id = '".$loginfo->getUserId()."'";
	$result = mysql_query($query);
	if($result) {
		while( $row  = mysql_fetch_row($result)){
			$registered[$row[0]] = $row[0];
		}
	}
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
		/*$( "#accordion" ).accordion({
			autoHeight: false,
			navigation: true
		});*/
		$("a.taketest").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){window.open("question.php?prgid="+$(this).attr("prgid"),"_self")})
		
		$("a.register").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){
			<?php if( $logedIn ){ ?>
				$( "#login-form" ).dialog( "open" );
				$(".redirectto").val("model/model_registerprogram.php?prgid="+$(this).attr("prgid"))
				//alert("Please login to register in program");
			<?php } 
			else{ ?>
				window.open("model/model_registerprogram.php?prgid="+$(this).attr("prgid"),"_self");
			<?php } ?>
	
		})
	});
</script>

<?php include('includes/middle.php'); ?>
<?php //include('includes/content_program.php'); ?>
<div class="demo">
	<div class="prgpad ui-widget-header">&nbsp;Program Details</div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<div id="accordion" class="insidecontent">
	<?php
	//echo $stdid;
	$sql="select * from es_prog where prog_id = ".($_GET['progid'] - $prgindx);
	$res = mysql_query($sql);
	while ($row = mysql_fetch_array($res)) {
	?>
		<div align="center"><h2 style="font-size:22px"><?php echo $row['prog_name']; ?></h2></div>
		<div>
			
			<div><div style="font-size:18px;">Program Description: </div><?php echo htmlentities ( $row['prog_short_desc']) ?></div><br>
			<div><div style="font-size:18px;">Registration Open Date:</div> <span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row[10]));?></span></div><br/>
			<div><div style="font-size:18px;">Registration Closing Date:</div><span style="color:blue"><?php echo date("l, jS \of F Y h:i A",strtotime($row[9]));?></span></div><br/>
			<div><div style="font-size:18px;">Mentor & Trainer Brief Profile:</div><?php echo htmlentities ($row[6]);?></div><br/>
			<div><div style="font-size:18px;">Course Prerequisite:</div><?php echo htmlentities ($row[7]);?></div><br/>
			<div><div style="font-size:18px;">Entrance Test Required:</div><?php if($row[8]) echo "Yes"; else echo "No";?></div>
			<div></div>		
			<br>
			<div>
				<?php if(!isset($registered[$row[0]])) { ?>
				<a class="register" prgid="<?php echo ($prgindx+$row[0]);?>">Register</a>
				<!--<?php if( isallowed($row[0],$loginfo->getUserId())) { ?>
				<a class="taketest" prgid="<?php echo ($prgindx+$row[0]);?>">Take Test</a>
				<?php } ?>-->
				<?php
				} else { 
				?>
				<span style="text-decoration:underline; color:#ffaa99">Registered</span>
				<?php
				}
				?>
			</div>
			
		</div>
	<?php
	}
	mysql_free_result($res);
	?>
	</div>
</div>
<?php include('includes/lower.php'); ?>
