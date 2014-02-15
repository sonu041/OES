<?php
$rootpath="../";
$qsnindx=1645;
$imgindx=1234;
$questionimagefileloc="qsnimage/";
include('includes/config.php');
function textimage($id,$box=100)
{
	$sql="select img_width,img_height from es_image where img_id=$id";
	$res=mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	$str="$sql";
	if($er!="")
	{
		 
	}
	else{
		$row=mysql_fetch_array($res);
		$width=$row["img_width"];
		$height=$row["img_height"];
		if($width<$height)
		{
			$height=($width*100)/$height;
			$width=100;
		} else{
			$width=($height*100)/$width;
			$height=100;
		}
		$str="<img src=\"img.php?token=".($id+$GLOBALS["imgindx"])."\" height=\"$height\" width=\"$width\"\>";
	}
	return $str;
}

if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}
if(isset($_POST["addqsn"]))
{
	/*print_r($_POST);
	print_r($_FILES);
	*/
	$sql="select addqsn(\"".($_POST["subid"]-$qsnindx)."\", \"".$_POST["qsn"]."\", \"" .$_POST["opt-a"]."\", \"". $_POST["opt-b"]."\", \"" .$_POST["opt-c"]."\", \"" .$_POST["opt-d"]."\", \"". $_POST["correctoption"]."\")";
	//echo $sql;
	$res=mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	if($er!="") 
		$_SESSION['qsnmngerror']=$er;
		
	$qsnid=mysql_fetch_array($res);
	
	$file=NULL;
	$error="";
	$isfile=false;
	//$imgname=array("qsnimg" => $rootpath.$questionimagefileloc.$qsnid[0]."-qsn.jpg", "opt-a-img" => $rootpath.$questionimagefileloc.$qsnid[0]."-qsn-a.jpg", "opt-b-img" => $rootpath.$questionimagefileloc.$qsnid[0]."-qsn-b.jpg", "opt-c-img" => $rootpath.$questionimagefileloc.$qsnid[0]."-qsn-c.jpg", "opt-d-img" => $rootpath.$questionimagefileloc.$qsnid[0]."-qsn-d.jpg");
	
	foreach($_FILES as $name=>$value){
		$file[$name]=0;
		if ((($value["type"] == "image/gif") || ($value["type"] == "image/jpeg") || ($value["type"] == "image/pjpeg")))	{
			if ($value["size"] > 500000)
			{
				$_SESSION['qsnmngerror'].="<br>one or more file is larger than 500kb, so discarded..";
				//redirect("question.php?subid=".$_GET['subid']);
			}
			elseif($value["error"] > 0) {
			}
			else{
				$openedfile = file_get_contents($_FILES[$name]["tmp_name"]);
	            $filehash = md5_file($_FILES[$name]["tmp_name"]);
	            $contents = addslashes($openedfile);
	            $size = getimagesize ($_FILES[$name]["tmp_name"]);
				//move_uploaded_file($_FILES[$name]["tmp_name"],$imgname[$name]);
				unlink($_FILES[$name]["tmp_name"]);
				$sql="select addimg(\"$filehash\",\"$contents\", \"".$size[1]."\", \"".$size[0]."\", \"".$size["mime"]."\",\"".$value["size"]."\")"; 
				
				$res=mysql_query($sql);
				$er=mysql_error();
				//echo $er;
				if($er!="") 
					$_SESSION['qsnmngerror'].="<br>".$sql."<br>";
					$_SESSION['qsnmngerror'].=$er;
				$row=mysql_fetch_array($res);
				$file[$name]=$row[0];
				$isfile=true;
			}
		}
	}
	if($isfile)
	{
		$sql="update es_qsn set qsn_img = \"".$file["qsnimg"]."\", qsn_img_a = \"".$file["opt-a-img"]."\", qsn_img_b = \"".$file["opt-b-img"]."\", qsn_img_c = \"".$file["opt-c-img"]."\", qsn_img_d = \"".$file["opt-d-img"]."\" where qsn_id=$qsnid[0]";
		//echo $sql;
		mysql_query($sql);
		$er=mysql_error();
		//echo $er;
		if($er!="") 
			$_SESSION['qsnmngerror'].="<br>".$er;
	}
	redirect("question.php?subid=".$_GET['subid']);
}
if(!isset($_GET["subid"]) && $_GET["subid"]=="")
{
	//die("1");
	redirect("submng.php");
}
$sql="SELECT * FROM es_sub WHERE sub_id = '". ($_GET['subid']-$qsnindx)."'";
$res = mysql_query($sql);
if($c_sub = mysql_fetch_array($res));
else {
		//die("2");
		redirect("submng.php");
}
mysql_free_result($res);

if(isset($_GET['delid']) && $_GET['delid'] > 0)
{
	$sql1="select count(ans_id) from es_ans where ans_qsn_id=\"".($_GET['delid'] )."\"";
	$val=mysql_fetch_array(mysql_query($sql1));
	if($val[0] == 0){
		@unlink("$rootpath".$questionimagefileloc.$_GET['delid']."-qsn.jpg");
		@unlink("$rootpath".$questionimagefileloc.$_GET['delid']."-qsn-a.jpg");
		@unlink("$rootpath".$questionimagefileloc.$_GET['delid']."-qsn-b.jpg");
		@unlink("$rootpath".$questionimagefileloc.$_GET['delid']."-qsn-c.jpg");
		@unlink("$rootpath".$questionimagefileloc.$_GET['delid']."-qsn-d.jpg");
		$sql="Delete from es_qsn where qsn_id=\"".($_GET['delid'] - 0)."\"";
	}
	else {
		//$_SESSION['qsnmngerror']="Invalid Subject";
		$sql="Update es_qsn set qsn_deleted=1 where qsn_id=\"".($_GET['delid'])."\"";
	}
	mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	if($er!="") 
		$_SESSION['qsnmngerror']=$er;
	else {
		$val=mysql_fetch_array(mysql_query("SELECT sub_qs_count FROM es_sub where sub_id=".($_GET['subid']-$qsnindx)));
		$val=$val[0]-1;
		$sql="UPDATE es_sub SET sub_qs_count='".$val."' WHERE sub_id='".($_GET['subid']-$qsnindx)."'";
		mysql_query($sql);
		$er=mysql_error();
		//echo $er;
		if($er!="") 
			$_SESSION['qsnmngerror']=$er;
	}
	//else 	$_SESSION['qsnmngerror']=$sql;
	redirect("question.php?subid=".$_GET["subid"]);
}
$error="";
if(isset($_SESSION['qsnmngerror']))
{
	$error=$_SESSION['qsnmngerror'];
	unset($_SESSION['qsnmngerror']);
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
<div id="testContent" class="tstpad" subid="<?php echo $c_sub[0]+$qsnindx ?>">
	<div class="qsnpad ui-widget-header  ui-corner-all"><?php echo $c_sub[1] ?></div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<div class="ui-corner-all ui-widget">
	<table width="100%" class="brdrtable">
	<tr><td colspan="10" class="brdrtable">
	<div><b>Subject :</b> <?php echo $c_sub[1] ?><span style="float:right"><b>Code :</b><?php echo $c_sub[2] ?></span></div>
	<div><b>Description:</b></div>
	<div><?php echo $c_sub[3] ?></div>
	</td></tr>
	<tr><td colspan="10" class="brdrtable"><a href="#" id="addnewqsn">Add question</a><span style="float:right"><a href="submng.php" >Back to subject Managment</a></span></td></tr>
	<?php
	$sql="SELECT * FROM es_qsn WHERE qsn_sub_id = '$c_sub[0]' and qsn_deleted=0";
	//echo $sql;
	$res = mysql_query($sql);
	if($row = mysql_fetch_array($res)) {
		$i=2;
	?>
		<tr>
			<th class="brdrtable">Question</th>
			<th class="brdrtable">Option A</th>
			<th class="brdrtable">Option A</th>
			<th class="brdrtable">Option A</th>
			<th class="brdrtable">Option A</th>
			<th class="brdrtable" width="60px">Correct Answer</th>
			<th class="brdrtable">Delete</th>
		</tr>
		<tr>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn.jpg\" width=100 height=100 />";//QSN
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-a.jpg\" width=100 height=100 />";//OPT A
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-b.jpg\" width=100 height=100 />";//OPT B
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>		
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-c.jpg\" width=100 height=100 />";//OPT C
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-d.jpg\" width=100 height=100 />";//OPT D
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php echo $row[$i];?></td>
			<td class="brdrtable"><a class="delqsn" delid="<?php echo $row[0] ?>" href="#">Delete</a></td>
		</tr>
		<?php
		}else{
			echo "<tr><td align=\"center\">No Question found for this subject</td></tr>";
		}
		while($row = mysql_fetch_array($res)) {
		$i=2;
	?>
		<tr>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn.jpg\" width=100 height=100 />";//QSN
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-a.jpg\" width=100 height=100 />";//OPT A
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-b.jpg\" width=100 height=100 />";//OPT B
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>		
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-c.jpg\" width=100 height=100 />";//OPT C
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php 
				echo $row[$i];
				$i++;
				//$row[3]=1;
				if($row[$i]!=0 && $row[$i]!='0')
				{
					//echo "<br /><img src=\"$rootpath".$questionimagefileloc.$row[0]."-qsn-d.jpg\" width=100 height=100 />";//OPT D
					echo "<br>".textimage($row[$i]);
				}
				$i++;
				?>
			</td>
			<td class="brdrtable"><?php echo $row[$i];?></td>
			<td class="brdrtable"><a class="delqsn" delid="<?php echo $row[0] ?>" href="#">Delete</a></td>
		</tr>
		<?php
		}
	mysql_free_result($res);

	?>
	</table>
	</div>
	<script type="text/javascript">
	<!--
		$(function(){
			$(".delqsn").click(function(){
				<?php
					if($c_sub['sub_qs_count']==1)
					echo "alert(\"A subjest atleast have one question\");return;\n" 
				?>
				if(confirm("Are Sure?\nThis is irrevesible."))
				{
					window.open("?subid="+$("#testContent").attr("subid")+"&delid="+$(this).attr("delid"),"_self")
				}
				return false;			
			})
			
			$(".brdrtable img").click(function(){
				img=$("<img />")
				.attr("src",$(this).attr("src"))
				
				clsbtn=$("<input type='button' value='Cancel' />")
				.button()
				.click(function(){
					diaclose();
					return false;
				});
				
				field=$("<div></div>")
				.append(img)
				.append(clsbtn)
				diaopen("View image",field)
			})			
			
			$("#addnewqsn").click(function(){
				//cont=$("<div></div>")
				try{
				field=$("<div></div>")
				p=$("<p></p>")
				.append('<label class="login" for="qsn">Question:</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="qsn" id="qsn"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="qsnimg">Image for Question:</label>')
				.append('<input class="login text ui-widget-content ui-corner-all"  type="file" name="qsnimg" id="qsnimg"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-a">Option A</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="opt-a" id="opt-a"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-a-img">Image for Option A</label>')
				.append('<input class="login text ui-widget-content ui-corner-all"  type="file" name="opt-a-img" id="opt-a-img"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-b">Option B</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="opt-b" id="opt-b"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-b-img">Image for Option B</label>')
				.append('<input class="login text ui-widget-content ui-corner-all"  type="file" name="opt-b-img" id="opt-b-img"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-c">Option C</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="opt-c" id="opt-c"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-c-img">Image for Option C</label>')
				.append('<input class="login text ui-widget-content ui-corner-all" type="file" name="opt-c-img" id="opt-c-img"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-d">Option D</label>')
				.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="opt-d" id="opt-d"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="opt-d-img">Image for Option D</label>')
				.append('<input class="login text ui-widget-content ui-corner-all" type="file" name="opt-d-img" id="opt-d-img"/>')
				field.append(p)
				
				p=$("<p></p>")
				.append('<label class="login" for="correctoption">Correct Option</label>')
				sel=$('<select class="login text ui-widget-content ui-corner-all required" name="correctoption" id="correctoption"></select>')
				.append('<option value="">Select</option>')
				.append('<option value="a">Option A</option>')
				.append('<option value="b">Option B</option>')
				.append('<option value="c">Option C</option>')
				.append('<option value="d">Option D</option>')
				p.append(sel)
				
				field.append(p)
				.css("height","400px")
				.css("overflow-y","scroll")
				.css("margin-bottom","10px")
				//.css("overflow-x","visible")

				sbtn=$("<input/>")
				.attr("type","submit")
				.attr("name","addqsn")
				//.class("login text ui-widget-content ui-corner-all")
				.val("Add")
				.button()	
				clbtn=$("<input/>")
				.attr("type","button")
				//.attr("name","addqsn")
				//.class("login text ui-widget-content ui-corner-all")
				.val("Cancel")
				.button()
				.click(function(){
					diaclose();
					return false;
				})
				
				form=$("<form></form>")
				.attr("enctype","multipart/form-data")
				.attr("method","POST")
				.append('<input type="hidden" name="subid" value="<?php echo $_GET["subid"] ?>" />')
				.append(field)		
				.append(sbtn)	
				.append(clbtn)
				//.submit(function(){return false;})
								
				
				cont=$("<div></div>")
				.append(form)
				diaopen("Add Question",cont)
				$(form).validate()		
				
				}catch(e){alert(e)}
				return false;
			})
		})
	//-->
</script>
</div>
<?php //include('includes/content_home.php'); ?>
<?php include('includes/lower.php'); ?>
