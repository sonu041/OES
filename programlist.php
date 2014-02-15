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

?>
<?php include('includes/upper.php'); ?>

<?php
if(isset($_SESSION['errorval']))
{
	$errormsg = $_SESSION['errorval'];
	echo  "<script type=\"text/javascript\">alert('$errormsg')</script>";
	$_SESSION['errorval'] = null;
}
?>

<?php include('includes/middle.php'); ?>
<style type="text/css">
.marg {margin-right:5px; padding: 5px}
.noborder {border-style: none;}
a:link, a:active, a:visited {
    
    color: #003366;
    
}
a:hover {
    color: #0066CC;
}
</style>
<?php //include('includes/content_program.php'); ?>
<div class="demo">
	<div class="prgpad ui-widget-header">&nbsp;Programs</div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<!--<div align="right">
	<label class="marg ui-corner-all ui-widget-content"><img style="" width="16" height="16" src="images/search_btn.gif" alt="" >&nbsp;&nbsp;<input class="noborder" type="text" id="searchstring"/></label>
	</div>-->
	<div id="accordion" class="insidecontent">
	
	</div>
</div>
<!-- js or css here -->

<script>
	//progs=new Array();
	<?php 
	//echo $stdid;
	$sql="select * from es_prog where prog_deleted = 0 and prog_start_date < ".date('Ymd')." and prog_expire_date > ".date('Ymd');
	//echo $sql;
	$res = mysql_query($sql);
	$progcount=0;
	$progs=null;
	while ($row = mysql_fetch_array($res)) {
	$isReg = isset($registered[$row['prog_id']]) ? 1 : 0;
	$progs[] = Array($row['prog_id']+$prgindx, $row['prog_name'], $row['prog_desc'], date("l, jS \of F Y h:i A",strtotime($row['prog_start_date'])), date("l, jS \of F Y h:i A",strtotime($row['prog_expire_date'])), strtoupper($row['prog_name']), strtoupper($row['prog_desc']),$isReg);

	}
	mysql_free_result($res);
	//echo count($progs)."\n";
	echo "progs = " . json_encode($progs);
	?>
	
	function renderProgram(srch){
		srch = srch.toUpperCase()
		//alert("renderProgram");
		accrd = $("#accordion")
		//alert(accrd)
		accrd.empty()
		for(it in progs)
		{
			//alert(progs[it][1])
			if(srch != "")
				if(progs[it][5].search(srch)==-1 && progs[it][6].search(srch)==-1)
					continue;
			a = $("<a style='text-decoration: none;'></a>")
			.attr("href","programdetails.php?progid=" + progs[it][0])
			.text(progs[it][1])
			h3 = $("<h3 style='font-size:18px; color:rgb(0,0,255); font-family: Arial; letter-spacing:1px; '></h3>")
			.append(a)

			p = $("<p></p>")
			
			div = $("<div></div>")
			.text(progs[it][2])
			p.append(div)

			div = $("<div></div>")
			.append("Registration Open Date: <span style=\"color:blue\">"+progs[it][3]+"</span>, ")
			.append("Registration Closing Date: <span style=\"color:blue\">"+progs[it][4]+"</span>")
			p.append(div);
			
			if(progs[it][7]==0){
				a = $("<a></a>")
				.addClass("register")
				.attr("prgid",progs[it][0])
				.append("Register")
				.css("cursor","pointer")
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
				div = $("<div></div>")
				.append(a)
				p.append(div)
			}
			else{
				a = $("<span></span>")
				//.addClass("register")
				//.attr("prgid",progs[it][0])
				.append("Registered")
				//.css("cursor","pointer")
				.css("text-decoration","underline")
				.css("color","#ffaa99")
				div = $("<div></div>")
				.append(a)
				p.append(div)
			}
			accrd
			.append(h3)
			.append(p)
			.append("<hr />")
		}
		//alert(accrd.text())
	}
	$(function() {
		$("#searchstring").keypress(function(){
			renderProgram($("#searchstring").val())
		})
		$("#searchstring").keyup(function(){
			renderProgram($("#searchstring").val())
		})
		renderProgram("")
	});
</script>
<?php include('includes/lower.php'); ?>
