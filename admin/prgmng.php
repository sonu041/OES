<?php
$rootpath="../";
$prgindx=12348;
$tstindx=15632; 
include('includes/config.php');
if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}
if(isset($_POST["addprg"]))
{
	//print_r($_POST); //die;
	//$strtym=date("Y-m-d H:i:s",strtotime($_POST["strtd"]));
	//$endtym=date("Y-m-d H:i:s",strtotime($_POST["expd"]));
	
	$attrib["name"] = "prog_name";
	$attrib["disbled"] = "prog_disbaled";
	$attrib["desc"] = "prog_desc";
	$attrib["srtdesc"] = "prog_short_desc";
	$attrib["mentor"] = "prog_mentor_profile";
	$attrib["prereq"] = "prog_prerequisite";
	$attrib["testreq"] = "prog_test_isrequired";
	$attrib["tstcd"] = "prog_test_id";
	$attrib["strtd"] = "prog_start_date";
	$attrib["expd"] = "prog_expire_date";

	$sql="";
	if(isset($_POST["addprg"]) && $_POST["addprg"]=="Add")//$_POST["progid"] < $prgindx)
	{
		//echo "here";
		$prgqua=0;
		if(isset($_POST['tstcd'])){
			$sql="select test_qua, test_totalquestion from es_test where test_id=".($_POST["tstcd"]-$tstindx);
			//echo $sql;
			$_POST["tstcd"] -= $tstindx;
			$row=mysql_fetch_array(mysql_query($sql));
			if($row[1]){
				$prgqua=floor(($row[0]/$row[1])*100);	
			}else{
				$prgqua=0;
			}
		}
		else {
			$prgqua=0;
		}
		//$sql="";
		$sql_attrib="";
		$sql_data="";
		foreach($attrib as $key => $value){
			if(isset($_POST[$key])){
				$sql_attrib .= $value . ", ";
				$sql_data .= "\"" . $_POST[$key] . "\", ";
			}
		}
		
		$sql = "insert into es_prog (" . $sql_attrib . "prog_test_qualifing_percent) values ( " . $sql_data . $prgqua . ")";
		
		//echo $sql;die;
		//$sql="insert into es_prog (prog_name, prog_desc, prog_test_id, prog_test_qualifing_percent, prog_short_desc, prog_mentor_profile, prog_prerequisite, prog_expire_date, prog_start_date, prog_disbaled, prog_test_isrequired) values(\"".$_POST["name"]."\", \"".$_POST["desc"]."\", \"".($_POST["tstcd"]-$tstindx)."\", \"".$prgqua."\", \"".$_POST["srtdesc"]."\", \"".$_POST["mentor"]."\", \"".$_POST["prereq"]."\", \"$endtym\", \"$strtym\", \"".$_POST["disbled"]."\", \"".$_POST["testreq"]."\")";
	}
	else {

		$sql_set="";
		foreach($attrib as $key => $value){
			if(isset($_POST[$key])){
				$sql_set .= $value . " = \"" . $_POST[$key] . "\", ";
			}
		}
		$sql_set = trim($sql_set, ", ");
		$sql="update es_prog set $sql_set where prog_id=\"".($_POST["progid"] - $prgindx)."\"";
		//echo $sql; die;
	}
	mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	if($er!="") 
		$_SESSION['prgmngerror']=$er;
	else{
		$_SESSION['prgmngerror']="Database updated successfully";

	}
	redirect("prgmng.php");
}



if(isset($_GET['delid']) && $_GET['delid'] > 0)
{
	//print_r($_GET);
	$sql="select count(sp_id) from es_std_prog where sp_prog_id=".($_GET['delid']-$prgindx);
	$row=mysql_fetch_array(mysql_query($sql));
	if($row[0]>0)
	{
		$sql="update es_prog set prog_deleted=1 where prog_id=".($_GET['delid']-$prgindx);
		
	}
	else{
		$sql="delete from es_prog where prog_id=".($_GET['delid']-$prgindx);
	}
	mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	if($er!="") 
		$_SESSION['prgmngerror']=$er;
	else{
		$_SESSION['prgmngerror']="Program deleted successfully";

	}
	redirect("prgmng.php");
	echo $sql;
	die();
}



$error="";
if(isset($_SESSION['prgmngerror']))
{
	$error=$_SESSION['prgmngerror'];
	unset($_SESSION['prgmngerror']);
}
$sql="select sp_prog_id, count(sp_id) from es_std_prog";
$res = mysql_query($sql);
$std=NULL;
while ($row = mysql_fetch_row($res)) {
	$std[$row[0]]=$row[1];
}
mysql_free_result($res);

$sql="select test_id, test_code, test_description from es_test where test_deleted=0";
$res = mysql_query($sql);
$test=NULL;
while ($row = mysql_fetch_row($res)) {
	$test[$row[0]+$tstindx]=$row[2];
}
mysql_free_result($res);

//$progs=NULL;
$progs[0]=array("-1", "", "", "", "", "", "", "", "", "", "", "", "", "");

$sql="SELECT prog_id, prog_name, prog_desc, prog_test_id, prog_test_qualifing_percent, prog_short_desc, prog_mentor_profile, prog_prerequisite, prog_expire_date, prog_start_date, prog_disbaled, prog_test_isrequired, prog_deleted, test_name, test_description FROM es_prog, es_test WHERE prog_deleted=0 and prog_test_id=test_id and prog_test_id is not NULL";
$res = mysql_query( $sql);
//echo $sql;
$count=1;
while ($row = mysql_fetch_row($res)) {
	//$progs[$count]=$row;
	//print_r($row);
	//echo "<br>";
	$progs[$count][0] =$row[0]+$prgindx;
	$progs[$count][1] =$row[1];
	$progs[$count][2] =$row[2];
	$progs[$count][3] =$row[4];
	$progs[$count][4] =$row[5]; 			
	$progs[$count][5] =$row[6]; 			
	$progs[$count][6] =$row[7]; 			
	$progs[$count][7] =$row[9];//date("m/d/Y H:i:s",strtotime($row[9]));
	$progs[$count][8] =$row[8];//date("m/d/Y H:i:s",strtotime($row[8]));;	
	$progs[$count][9] =$row[10]=='0' ? "No":"Yes";			//Disabled
	$progs[$count][10] =$row[11]=='0'? "No":"Yes";			//Test Required
	$progs[$count][11] =$row[13];					//Test Name
	$progs[$count][12] =isset($std[$row[0]]) ? $std[$row[0]] :'0';
	
//	$progs[$count][8] ="Delete";
	$count++;
}
mysql_free_result($res);

$sql="SELECT prog_id, prog_name, prog_desc, prog_test_id, prog_test_qualifing_percent, prog_short_desc, prog_mentor_profile, prog_prerequisite, prog_expire_date, prog_start_date, prog_disbaled, prog_test_isrequired, prog_deleted FROM es_prog WHERE prog_deleted=0 and prog_test_id is NULL and prog_test_isrequired=0";
$res = mysql_query( $sql);
//$count=1;
//echo " diff ".$sql;
while ($row = mysql_fetch_row($res)) {
	//$progs[$count]=$row;
	//print_r($row);
	//echo "<br>";
	$progs[$count][0] =$row[0]+$prgindx;
	$progs[$count][1] =$row[1];
	$progs[$count][2] =$row[2];
	$progs[$count][3] =$row[4];
	$progs[$count][4] =$row[5]; 			
	$progs[$count][5] =$row[6]; 			
	$progs[$count][6] =$row[7]; 			
	$progs[$count][7] =$row[9];//date("m/d/Y H:i:s",strtotime($row[9]));
	$progs[$count][8] =$row[8];//date("m/d/Y H:i:s",strtotime($row[8]));;	
	$progs[$count][9] =$row[10]=='0' ? "No":"Yes";			//Disabled
	$progs[$count][10] =$row[11]=='0'? "No":"Yes";			//Test Required
	$progs[$count][11] ="NA";//$row[13];					//Test Name
	$progs[$count][12] =isset($std[$row[0]]) ? $std[$row[0]] :'0';
	
//	$progs[$count][8] ="Delete";
	$count++;
}
mysql_free_result($res);


?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<style type="text/css">
.tstpad{ padding:2px;}
img.prgopt{height: 20px; width: 20px;}
.brdrtable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
label.error {  color: red; padding-right: 50px; padding-bottom: 50px; vertical-align: top; }
p { clear: both; }
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
#demo-frame > div.demo { padding: 10px !important; };

</style>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<?php include('includes/middle.php'); ?>
<div id="testContent" class="tstpad insidecontent" subid="<?php //echo $c_sub[0]+$prgindx; ?>">
	<div class="prgpad ui-widget-header">Program Manager</div>
	<?php if($error!="") echo'<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'.$error.'</div>'; ?>
	<div id="prgmngcont" class="ui-corner-all ui-widget">
	<center><a href="#" id="addnew">Add new program</a></center>
	</div>
	<script type="text/javascript">
	//<!--
		progs=new Array();
		<?php echo isset($progs)?  "progs=".json_encode($progs).";\n":";\n" ?>
		tests=<?php echo json_encode($test) ?>;
		tblhd=["prog_id", "Name", "Description", "Qualifing Percentage", "Short Description", "Mentor's Profile","Prerequisite", "Started on", "Expire on", "Disbaled","Test Required","Test","No of Student", "Action"]
		/*poplljkf=""
		for ( popop in tblhd)
			poplljkf += popop + " => " + tblhd[popop] + "\n"
		alert(poplljkf)*/
		$(function(){
			try{
				mntbl=$("<table></table>")
				.attr("width","99%")
				.attr("align","center")
				.addClass("brdrtable")
				if(progs !=null && progs.length > 1){
					rw=$("<tr></tr>")
					for( j=1; j<tblhd.length;j++)
					{
						cell=$("<th></th>")
						.addClass("brdrtable")
						.text(tblhd[j])
						
						rw.append(cell)
					}				
					mntbl.append(rw)	
				}
				else{
					rw=$("<tr></tr>")
					cell=$("<th></th>")
					.text("No program found")
					.attr("colspan",10)
					rw.append(cell)
					mntbl.append(rw)
				}
				
				for(i in progs)
				{
					if(i==0) continue
					rw=$("<tr></tr>")
					.attr("prgid",i)
					for(j=1; j<progs[i].length;j++ )
					{
						cell=$("<td></td>")
						.addClass("brdrtable")
						.text(progs[i][j])	
						rw.append(cell)
					}				
					cell=$("<td></td>")
					.addClass("brdrtable")
					if(progs[i][j-1]==0){
						cell.html('<a href="#" class="deleteprg" delid="'+i+'">Delete</a><br /><a href="#" class="editprg" editid="'+i+'">Edit</a><br /><a href="#" class="fylprg" fylid="'+i+'">File</a>')	
					}
					else
						cell.html('<a href="#" class="editprg" editid="'+i+'">Edit</a><br /><a href="#" class="fylprg" fylid="'+i+'">File</a>')	
					rw.append(cell)
					mntbl.append(rw)
				}
				cont=$("#prgmngcont")
				cont.append(mntbl)
			
			}catch(e){alert(e);}
			
			$("#addnew").click(function(){
				openform(0);
				return false;
			})
			$(".deleteprg").click(function(){
				if(confirm("R you sure?")){
					//alert(progs[$(this).attr("delid")][0]);
					window.open("prgmng.php?delid="+progs[$(this).attr("delid")][0],"_self")
				}
				return false;
			})
			
			$(".fylprg").click(function(){
				//if(confirm("R you sure?")){
					//alert("fylmng.php?progid="+progs[$(this).attr("fylid")][0]);
					window.open("fylmng.php?progid="+progs[$(this).attr("fylid")][0],"_self")
				//}
				return false;
			})
			
			$(".editprg").click(function(){
				//alert($(this).parent().parent().html())
				openform($(this).attr("editid"))
				return false;
			})
		})
		function openform(id)
		{
			try{
			field=$("<table></table>")
			// ROW start
			tr=$("<tr></tr>")
			//Cell Start :: Prog Name
			td=$("<td></td>")
			.append('<label class="login" for="name">Program Name:</label>')
			.append('<input class="login text ui-widget-content ui-corner-all required" value="'+progs[id][1]+'" type="text" name="name" id="name"/>')
			//.attr("colspan",2)
			.attr("width","50%")
			tr.append(td)
			//Cell  end
			
			//Cell Start :: Disabled?
			//alert(progs[id][9])
			if(progs[id][9]=="Yes"){
				td=$("<td></td>")
				.append('<label class="login" >Disabled:</label>')
				.append('Yes <input class="ui-widget-content ui-corner-all required" value="1" type="radio" name="disbled" id="disbledYes" checked="checked"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')
				.append('No <input class="ui-widget-content ui-corner-all required" value="0" type="radio" name="disbled" id="disbledNo"/>')
			}
			else /*if(progs[id][9]=="No")*/{
				td=$("<td></td>")
				.append('<label class="login" >Disabled:</label>')
				.append('Yes <input class="ui-widget-content ui-corner-all required" value="1" type="radio" name="disbled" id="disbledYes"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')
				.append('No <input class="ui-widget-content ui-corner-all required" value="0" type="radio" checked="checked" name="disbled" id="disbledNo"/>')
			}
			/*else{
				td=$("<td></td>")
				.append('<label class="login" >Disabled:</label>')
				.append('Yes <input class="ui-widget-content ui-corner-all required" value="Yes" type="radio" name="disbled" id="disbledYes"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')
				.append('No <input class="ui-widget-content ui-corner-all required" value="No" type="radio" name="disbled" id="disbledNo"/>')
			}*/
			
			//.attr("colspan",2)
			tr.append(td)
			//Cell end
			field.append(tr)
			//Row end
			
			
			//Row start
			tr=$("<tr></tr>")
			//Cell start :: Desc
			td=$("<td></td>")
			.append('<label class="login" for="desc">Desription:</label>')
			.append('<textarea class="login text ui-widget-content ui-corner-all required"  type="text" name="desc" id="desc">'+progs[id][2]+'</textarea>')
			tr.append(td)
			//Cell end			
			
			
			//Cell start :: Shrt Desc
			td=$("<td></td>")
			.append('<label class="login" for="srtdesc">Short Desription:</label>')
			.append('<textarea class="login text ui-widget-content ui-corner-all required"  type="text" name="srtdesc" id="srtdesc">'+progs[id][4]+'</textarea>')
			tr.append(td)
			//Cell Ends
			field.append(tr)
			//Row Ends

			//Row start
			tr=$("<tr></tr>")
			//Cell start :: Mentor
			td=$("<td></td>")
			.append('<label class="login" for="mentor">Mentor Profile:</label>')
			.append('<textarea class="login text ui-widget-content ui-corner-all"  type="text" name="mentor" id="mentor">'+progs[id][5]+'</textarea>')
			tr.append(td)
			//Cell End
			
			//Cell Start :: Prerequisite
			td=$("<td></td>")
			.append('<label class="login" for="prereq">Prerequisite:</label>')
			.append('<textarea class="login text ui-widget-content ui-corner-all"  type="text" name="prereq" id="prereq">'+progs[id][6]+'</textarea>')
			tr.append(td)
			//Cell end
			field.append(tr)
			//Row end
			
			if(id==0)
			{
				//Row Start
				tr=$("<tr></tr>")
				//Cell Start :: required?
				td=$("<td></td>")
				//.append('<label class="login" for="testreq">Test Required</label>')
				//sel=$('<select class="login text ui-widget-content ui-corner-all required" name="testreq" id="testreq"></select>')
				if(progs[id][10]=="No"){
					td=$("<td></td>")
					.append('<label class="login" >Test Required:</label>')
					.append('Yes <input class="ui-widget-content ui-corner-all required" value="1" type="radio" name="testreq" id="testreqYes" onclick="testreqfun(this)" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')
					.append('No <input class="ui-widget-content ui-corner-all required" value="0" type="radio" name="testreq" id="testreqNo"  onclick="testreqfun(this)" checked="checked" />')
					
					//sel
					//.append('<option value="">Select</option>')
					//.append('<option selected="selected" value="1">Yes</option>')
					//.append('<option value="0">No</option>')
				} else {
					td=$("<td></td>")
					.append('<label class="login" >Test Required:</label>')
					.append('Yes <input class="ui-widget-content ui-corner-all required" value="1" type="radio" name="testreq" id="testreqYes" onclick="testreqfun(this)" checked="checked" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;')
					.append('No <input class="ui-widget-content ui-corner-all required" value="0" type="radio" name="testreq" id="testreqNo"  onclick="testreqfun(this)" />')
				}
				//td.append(sel)			
				tr.append(td)
				//Cell End
			
				//Cell Start :: Test Name
				td=$("<td></td>")
				.append('<label class="login" for="tstcd">Test</label>')
				sel=$('<select class="login text ui-widget-content ui-corner-all required" name="tstcd" id="tstcd"></select>')
				.append('<option value="">Select</option>')
				for(x in tests){
					sel.append('<option value="'+x+'">'+tests[x]+'</option>')
				}
				td.append(sel)
				tr.append(td)
				field.append(tr)
			}

			//if(progs[id][10] != "No")
			{
				
				//Row Start
				tr=$("<tr></tr>")
				//Cell Start :: Started on
				td=$("<td></td>")
				.append('<label class="login" for="strtd">Started on:</label>')
				//.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="percent" id="percent"/>')
				dps=$("<input />")
				.addClass("login text ui-widget-content ui-corner-all required")
				.attr("type","text")
				.attr("name","strtd")
				.attr("id","strtd")
				.val(progs[id][7])
				//.datetimepicker()
				td.append(dps)
				tr.append(td)
				//Cell end
				
				//Cell start :: Expire on
				td=$("<td></td>")
				.append('<label class="login" for="expd">Expire on:</label>')
				//.append('<input class="login text ui-widget-content ui-corner-all required"  type="text" name="percent" id="percent"/>')
				dpe=$("<input />")
				.addClass("login text ui-widget-content ui-corner-all required")
				.attr("type","text")
				.attr("name","expd")
				.attr("id","expd")
				.val(progs[id][8])
				//.datetimepicker()
				td.append(dpe)
				tr.append(td)
				//Cell end
				field.append(tr)
				//Row end
				//T
			}
			
			dps.datetimepicker({
				//dateFormat: 'yyyy-mm-dd',
				//formattedDateTime: 'yyyy-mm-dd hh:mm:ss',				
				//formattedDateTime: 'dd/mm/yyyy hh:mm:ss',
				//showSecond: true,
			    timeFormat: 'hh:mm:ss',
			    dateFormat: 'yy-mm-dd',
			    onClose: function(dateText, inst) {
			        var endDateTextBox = dpe;
			        if (endDateTextBox.val() != '') {
			            var testStartDate = new Date(dateText);
			            var testEndDate = new Date(endDateTextBox.val());
			            if (testStartDate > testEndDate)
			                endDateTextBox.val(dateText);
			        }
			        else {
			            endDateTextBox.val(dateText);
			        }
			    },
			    onSelect: function (selectedDateTime){
			        var start = $(this).datetimepicker('getDate');
			        dpe.datetimepicker('option', 'minDate', new Date(start.getTime()));
			    }
			});
			dpe.datetimepicker({
				//dateFormat: 'yyyy-mm-dd',
				//showSecond: true,
				timeFormat: 'hh:mm:ss',
			    dateFormat: 'yy-mm-dd',
			    onClose: function(dateText, inst) {
			        var startDateTextBox = dps;
			        if (startDateTextBox.val() != '') {
			            var testStartDate = new Date(startDateTextBox.val());
			            var testEndDate = new Date(dateText);
			            if (testStartDate > testEndDate)
			                startDateTextBox.val(dateText);
			        }
			        else {
			            startDateTextBox.val(dateText);
			        }
			    },
			    onSelect: function (selectedDateTime){
			        var end = $(this).datetimepicker('getDate');
			        dps.datetimepicker('option', 'maxDate', new Date(end.getTime()) );
			    }
			});					
			
			
			
			field
			.css("height","400px")
			.css("overflow-y","scroll")
			.css("margin-bottom","10px")
			.width("100%")

			sbtn=$("<input/>")
			.attr("type","submit")
			.attr("name","addprg")
			.val("Add")
			.button()
			if(id!=0)
				sbtn.val("Update");
			clbtn=$("<input/>")
			.attr("type","button")
			.val("Cancel")
			.button()
			.click(function(){
				diaclose();
				return false;
			})
			
			form=$("<form></form>")
			.attr("enctype","multipart/form-data")
			.attr("method","POST")
			.append('<input type="hidden" name="progid" value="'+progs[id][0]+'" />')
			.append(field)		
			.append(sbtn)	
			.append(clbtn)
			
							
			
			cont=$("<div></div>")
			.append(form)
			diaWidth=$(document).width()-100
			//alert(diaWidth)
			if(id!=0)
				diaopenWidthModal("Edit program",cont,diaWidth)
			else
				diaopenWidthModal("Add program",cont,diaWidth)
			$(form).validate()
			}catch(e){alert(e)}
		}
		
		function testreqfun(ele)
		{
			//alert($(ele).val())
			//alert($(ele).val() + " " + $(ele).attr("checked"))
			if($(ele).val()=="1")
			{
				try{
				$("#tstcd").css("visibility","")
				.removeAttr("disabled")
				//$("#strtd").removeAttr("disabled")
				//$("#expd").removeAttr("disabled")
				} catch(e){
					alert(e)
				}
			}
			else {
				$("#tstcd").css("visibility","hidden")
				.attr("disabled","disabled")
				//$("#strtd").attr("disabled","disabled")
				//$("#expd").attr("disabled","disabled")
			}
		}
		
	//-->
</script>
</div>
<?php //include('includes/content_home.php'); ?>
<?php include('includes/lower.php'); ?>
