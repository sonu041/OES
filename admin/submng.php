<?php
$rootpath="../";
$qsnindx=1645;
include('includes/config.php');
if(isset($_GET['token']) && !$loginfo->isLoggedIn())
{
	$data["loggedin"]="NO";
	sendjsondata();
}
if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}
if(isset($_POST['subid']))
{
	if($_POST['subid'] < $qsnindx)
	{
		//insert
		$sql="insert into es_sub (sub_name,sub_code,sub_desc) values('".$_POST['subname']."', '".$_POST['subcode']."', '".$_POST['subdesc']."')";
	}
	else {
		$sql="UPDATE es_sub SET sub_name='".$_POST['subname']."', sub_code='".$_POST['subcode']."', sub_desc='".$_POST['subdesc']."' WHERE sub_id='".($_POST['subid']-$qsnindx)."'";
	}
	mysql_query($sql);
	$er=mysql_error();
	if($er!="") 
		$_SESSION['submngerror']=$er;
	//else 	$_SESSION['submngerror']=$sql;
	redirect("submng.php");
}
if(isset($_GET['delid']) && $_GET['delid'] > $qsnindx)
{
	$sql1="select count(ms_mod_id) from es_mod_sub where ms_sub_id=\"".($_GET['delid'] - $qsnindx)."\"";
	$val=mysql_fetch_array(mysql_query($sql1));
	if($val[0] == 0){
		//mysql_query("set autocomi");
		$sql="Delete from es_qsn where qsn_sub_id=\"".($_GET['delid'] - $qsnindx)."\"";
		mysql_query($sql);
		$er=mysql_error();
		if($er!="") 
			$_SESSION['submngerror']=$er;
		else{
			$sql="Delete from es_sub where sub_id=\"".($_GET['delid'] - $qsnindx)."\"";
			mysql_query($sql);
			$er=mysql_error();
			if($er!="") 
				$_SESSION['submngerror']=$er;
		}
	}
	else {
		$_SESSION['submngerror']="Invalid Subject";
	}
	//else 	$_SESSION['submngerror']=$sql;
	redirect("submng.php");
}
$error="";
if(isset($_SESSION['submngerror']))
{
	$error=$_SESSION['submngerror'];
	unset($_SESSION['submngerror']);
}
?>
<?php include('includes/upper.php'); ?>
<style type="text/css">
.tstpad{ padding:2px;}
img.qsnopt{height: 20px; width: 20px;}
.brdrtable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
#demo-frame > div.demo { padding: 10px !important; };
</style>
<script type="text/javascript" src="js/dialog.js"></script>
<?php include('includes/middle.php'); ?>
<div id="testContent" class="tstpad insidecontent">
	<div class="qsnpad ui-widget-header">Subject Manager</div>
	<div class="ui-corner-all ui-widget">
	<center><a href="#" id="addnew">Add new Subject</a></center>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<table class="brdrtable" width="100%"> 
	<?php
		$sql="select ms_sub_id, count(ms_mod_id), max(ms_no_qsn) from es_mod_sub group by ms_sub_id;";		
		$res = mysql_query($sql);
		$subject=NULL;
		while ($row = mysql_fetch_array($res)) {
			$subject[$row[0]][0]=$row[1];
			$subject[$row[0]][1]=$row[2];
		}
		//print_r($subject);
		$sql="SELECT * FROM es_sub";
		$res = mysql_query($sql);
		if($row = mysql_fetch_array($res)) {
	?>
		<tr subid=-11>
			<th class="brdrtable" width="100px"><strong>Subject Name</strong></th>
			<th class="brdrtable" width="60px"><strong>Subject Code</strong></th>
			<th class="brdrtable"><strong>Description</strong></th>
			<th class="brdrtable" width="70px"><strong>Number of question</strong></th>
			<th class="brdrtable" width="60px"><strong>Number of modules</strong></th>
			<th class="brdrtable" width="100px"><strong>Maximum number of question</strong></th>			
			<th class="brdrtable" width="80px"><strong>Manage questions</strong></th>
		</tr>
		<tr subid="<?php echo $row[0]+$qsnindx;?>">
			<td class="brdrtable"><?php echo $row[1];?></td>
			<td class="brdrtable"><?php echo $row[2];?></td>
			<td class="brdrtable"><?php echo $row[3];?></td>
			<td class="brdrtable"><?php echo $row[4];?></td>
			<td class="brdrtable"><?php echo isset($subject[$row[0]])?$subject[$row[0]][0]:0;?></td>
			<td class="brdrtable"><?php echo isset($subject[$row[0]])?$subject[$row[0]][1]:0;?></td>			
			<td class="brdrtable"><a href="question.php?subid=<?php echo ($row[0]+$qsnindx);?>">Manage</a>
			<?php
			if(!isset($subject[$row[0]]))
				echo "<br><a class=\"delsub\" delid=\"".($row[0]+$qsnindx)."\" href=\"#\" title=\"Delete this Subject\">Delete</a>";
			?>
			</td>
		</tr>
		<?php
		}else{
			echo "<tr><td align=\"center\">No subject found</td></tr>";
		}
		
		while ($row = mysql_fetch_array($res)) {
	?>
		<tr subid="<?php echo $row[0]+$qsnindx;?>">
			<td class="brdrtable"><?php echo $row[1];?></td>
			<td class="brdrtable"><?php echo $row[2];?></td>
			<td class="brdrtable"><?php echo $row[3];?></td>
			<td class="brdrtable"><?php echo $row[4];?></td>
			<td class="brdrtable"><?php echo isset($subject[$row[0]])?$subject[$row[0]][0]:0;?></td>
			<td class="brdrtable"><?php echo isset($subject[$row[0]])?$subject[$row[0]][1]:0;?></td>			
			<td class="brdrtable"><a href="question.php?subid=<?php echo ($row[0]+$qsnindx);?>">Manage</a>
			<?php
			if(!isset($subject[$row[0]]))
				echo "<br><a class=\"delsub\" delid=\"".($row[0]+$qsnindx)."\" href=\"#\" title=\"Delete this Subject\">Delete</a>";
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
	try{
	$(function(){
		//alert("h");
		$(".brdrtable tr").hover(function(){
			$(this).css("background-color","#E9E9E4");
		},function(){
			$(this).css("background-color","#fff");
		})
		.css("cursor","pointer")
		.dblclick(function(){
			//alert($(this).text());
			subid=$(this).attr("subid")
			if(subid <=0) return;
			values=[]
			$(this).children().siblings().each(function(index){
				values[index]=$(this).text()
			})
			
			//alert(values);
			//alertdia($(this).children().next(":first").text())
			diahtml=$("<div></div>")
			.html('<form method="post" id="editsub"><input type="hidden" name="subid" value="'+subid+'" /><fieldset><label class="login" for="subname">Subject Name:</label><input class="login text ui-widget-content ui-corner-all"  type="text" name="subname" id="subname" value="'+values[0]+'"/><label class="login" for="subcode">Subject Code:</label><input class="login text ui-widget-content ui-corner-all" type="text" name="subcode" id="subcode" value="'+values[1]+'"/><label class="login" for="subcode">Description:</label><textarea class="login text ui-widget-content ui-corner-all" name="subdesc" id="subdesc">'+values[2]+'</textarea></fieldset></form>')
			diaopen("Update subject",diahtml);
			btn=$("<input />").attr("type","button").val("Update").button().click(function(){
				subname=$.trim($("#subname").val())
				subdesc=$.trim($("#subdesc").val())
				subcode=$.trim($("#subdesc").val())
				if(subdesc=="" || subdesc==null || subname=="" || subname==null || subcode=="" || subcode==null)
				{
					alert("Field cannot be empty");
				}
				else{
					//alert("here");
					$("#editsub").submit();
				}
			})
			diahtml.append(btn)
			btn=$("<input />").attr("type","button").val("Cancel").button().click(function(){
				diaclose();
			})
			diahtml.append(btn)
		})
		
		$("#addnew").click(function(){
			diahtml=$("<div></div>")
			.html('<form method="post" id="editsub"><input type="hidden" name="subid" value="-1" /><fieldset><label class="login" for="subname">Subject Name:</label><input class="login text ui-widget-content ui-corner-all" type="text" name="subname" id="subname" value=""/><label class="login" for="subcode">Subject Code:</label><input class="login text ui-widget-content ui-corner-all" type="text" name="subcode" id="subcode" value=""/><label class="login" for="subcode">Description:</label><textarea class="login text ui-widget-content ui-corner-all" name="subdesc" id="subdesc"></textarea></fieldset></form>')
			diaopen("New subject",diahtml);
			btn=$("<input />").attr("type","button").val("Add").button().click(function(){
				subname=$.trim($("#subname").val())
				subdesc=$.trim($("#subdesc").val())
				subcode=$.trim($("#subdesc").val())
				if(subdesc=="" || subdesc==null || subname=="" || subname==null || subcode=="" || subcode==null)
				{
					alert("Field cannot be empty");
				}
				else{
					//alert("here");
					$("#editsub").submit();
				}
			})
			diahtml.append(btn)
			btn=$("<input />").attr("type","button").val("Cancel").button().click(function(){
				diaclose();
			})
			diahtml.append(btn)
			return false;
		})
		
		$(".delsub").click(function(){
			if(confirm("Are Sure?\nThis is irrevesible."))
			{
				window.open("?delid="+$(this).attr("delid"),"_self")
			}
			return false;			
		})
	});
	}catch(e){alert("h")}
	//-->
</script>
	</div>
</div>
<?php include('includes/lower.php'); ?>
