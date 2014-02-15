<?php
#print_r($_SERVER);die;
$prgindx=12348; 
$modindx=5732;
$imgindx=1234;
include_once('includes/config.php');
$data["error"]="";
$data["loggedin"]="YES";

/*
 *  Function to conversion between mysqldate format to phpdate fromat
 */
function mysql_2_php_date($str)
{
	$str=trim($str);
	//'2012-05-14 10:19:30';
	list($day,$time)=explode(" ", $str);
	list($hour,$minute,$second)=explode(":", $time);
	list($year,$month,$day)=explode("-", $day);
	return mktime($hour,$minute,$second,$month,$day,$year);
}

/*
 * Checks whether the current user is allowed for this test or not
 */
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

/*
 *  generate image text for qsn and option
 */
function textimage($id,$box=400)
{
	if($id==0)
		return "";
	$sql="select img_width,img_height from es_image where img_id=$id";
	$res=mysql_query($sql);
	$er=mysql_error();
	//echo $er;
	$str="";
	if($er!="")
	{
		 
	}
	else{
		$row=mysql_fetch_array($res);
		$width=$row["img_width"];
		$height=$row["img_height"];
		if($width<$height)
		{
			$height=($width*$box)/$height;
			$width=$box;
		} else{
			$width=($height*$box)/$width;
			$height=$box;
		}
		$str="<img src=\"img.php?token=".($id+$GLOBALS["imgindx"])."\" height=\"$height\" width=\"$width\"\>";
	}
	return $str;
}

/*
 *  Send json information to client
 */
function sendjsondata(){
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode( $GLOBALS["data"]);
	endpage();
}

/*
 *  Send error msg to client
 */
function send_error($str="")
{
	$data=null;
	$data["error"]=$str;
	sendjsondata();
}

/*
 *  log in checking, when this page is accessed via ajax
 */
if(isset($_GET['token']) && !$loginfo->isLoggedIn())
{
	$data["loggedin"]="NO";
	sendjsondata();
}

/*
 *  log in checking, for normal page request
 */
$progid=-1;
if(!$loginfo->isLoggedIn())
{
	redirect("index.php");
}

if(!isset($_GET['spid']) || $_GET['spid'] <= 0)
{
	redirect("myprograms.php");
}

$spid=$_GET["spid"];
if ( isset($_GET['prgid'])  && $_GET['prgid'] > $prgindx){
	$progid=$_GET['prgid']-$prgindx;
} 
else{
	// Write code to retrieve prgid from database
	$sql="select sp_prog_id from es_std_prog  where sp_status='registered' and sp_id=".$spid;
	$res=mysql_query($sql);
	$er=mysql_error();
	if($er!="") 
		redirect("myprograms.php");
	if( $progval=mysql_fetch_array($res))
	{
		$progid=$progval[0];
	}
	else{
		redirect("myprograms.php");
	}
}
//echo $progid;
if(!isset($_GET['token'])){
	if(!$loginfo->isCompleted()){
		//redirect("profileedit.php");
		echo "<html><body><script type=\"text/javascript\">";
		echo "alert(\"You can not give test without completing your profile.\");";
		echo "window.open('profileedit.php','_self');";
		echo "</script></body></html>";
	}
	$sql = "select prog_name, prog_expire_date from es_prog where prog_test_isrequired=1 and prog_expire_date >= '".date("Y-m-d")."' and prog_start_date <= '".date("Y-m-d")."' and prog_id = ".$progid;
	$res = mysql_query($sql);
	$er = mysql_error();
	if($er!=""){
		redirect("myprograms.php");
	}
	if($psoad=mysql_fetch_array($res)){}
	else
		redirect("myprograms.php");
}

$studid=$loginfo->getUserId();
$testid=1;

$qsnno=1;
//echo isallowed($progid,$studid)?1:0; die;
/*if(!isset($_GET['token']) && !isallowed($progid,$studid)){
	//echo "jkd:$progid,$studid";endpage();
	redirect("programs.php");
}/**/
//Send very first frame to client
//print_r($_GET);

if(isset($_GET['token']) && $_GET['token']=="finished")
{
	unset($_SESSION["examstarted"]);
	$data["done"]="NO";
	if(isset($_GET["spid"])){
		$sql="SELECT ans_id, ans_ans, qsn_correct_answer FROM es_ans, es_qsn where ans_sp_id=".$_GET["spid"]." and qsn_id=ans_qsn_id and ans_ans != 'none'";
		$res=mysql_query($sql);
		$ans=null;
		while($row=mysql_fetch_array($res)){
			if($row['ans_ans']!='none')
				$ans[$row['ans_id']]= $row['ans_ans']==$row['qsn_correct_answer'] ? 1:2;
			//else
			//	$ans[$row['ans_id']]=0;
		}
		$data["output"]="Attemp: ".count($ans)."\n";
		if(count($ans)>0){
			foreach($ans as $ansid=>$ans_is_correct){
				$sql="update es_ans set ans_is_correct=$ans_is_correct where ans_id=$ansid";
				mysql_query($sql);
				$er=mysql_error();
				if($er!="") 
					$data["output"].="\nErorr in my sql ";//.$er;
				/*else
					$data["output"].="\nsql: ".$sql;*/
			}
			
			//$data["output"]="".count($ans).":";
			//$data["output"]="Test ";
		}
		$sql="select count(ans_id),ans_mod_id,mod_name,ans_is_correct,mod_qua from es_ans,es_module where mod_id=ans_mod_id and ans_sp_id=".$_GET["spid"]." group by ans_mod_id,ans_is_correct";
		$res = mysql_query( $sql);
		$results=null;
		//echo $sql;
		while ($row = mysql_fetch_row($res)) {
			$results[$row[1]]["name"]=$row[2];
			$results[$row[1]][$row[3]]=$row[0];
			$results[$row[1]]['mod_qua']=$row[4];
		}
		mysql_free_result($res);
		//var_dump($results);
		$sql="INSERT INTO es_mod_result (mr_sp_id, mr_mod_id, mr_tot_qsn, mr_correct, mr_worng,mr_is_qua) VALUES";
		$overresults["tot_qsn"]=0;
		$overresults["tot_crrct"]=0;
		$overresults["passed"]=1;
		foreach($results as $key=>$value)
		{
			for($i=0;$i<3;$i++)
			{
				if(!isset($results[$key][$i]))
				{
					$results[$key][$i]=0;
					//echo $key,$i,$results[$key][$i];
				}
			}
			$results[$key]["attemps"]=$results[$key][1]+$results[$key][2];
			$results[$key]["totqsn"]=$results[$key][1]+$results[$key][2]+$results[$key][0];
			$results[$key]["passed"]= $results[$key]['mod_qua'] <= $results[$key][1] ? 1:0;
	
			$overresults["tot_qsn"] +=$results[$key]["totqsn"];
			$overresults["tot_crrct"] +=$results[$key][1];
			$overresults["passed"] = ($overresults["passed"]==1 && $results[$key]["passed"]==1) ? 1:0;
	
			$sql.="(".$_GET["spid"].",$key,".$results[$key]["totqsn"].",".$results[$key][1].",".$results[$key][2].", ".$results[$key]["passed"]."), ";
		}
		
		$sql=trim($sql, " ,");
		//$data["error"]=$sql;
		$res=mysql_query($sql);
		$er=mysql_error();
		//echo $er;
		$str="";
		if($er!="")
		{
			 $data["error"]="Error in mysql";//.": ".$er.":".$sql.":colcount".$_SESSION["Collcout"];
			 //unset($_SESSION["Collcout"]);
		}
		$sql="SELECT test_qua FROM es_prog,es_test where prog_test_id=test_id and prog_id=".$progid;
		$test_qua=mysql_fetch_array(mysql_query($sql));
		$overresults["passed"] = ($overresults["passed"] ==1 && $overresults["tot_crrct"]>=$test_qua[0])?1:0;
		$results["test"]=$overresults;
		//'2012-05-14 10:19:30';
		$sql="update es_std_prog set sp_is_qualified=".$overresults["passed"].", sp_status='over'";
		$sql .=", sp_test_end_time='".date("Y-m-d H:i:s")."' where sp_id=".$_GET["spid"];
		//echo $sql;
		$res=mysql_query($sql);
		$er=mysql_error();
		//echo $er;
		$str="";
		if($er!="")
		{
			 $data["error"]="Error in mysql";//.": ".$er." : ".$sql ;
		}
		
		$data["done"]="YES";
		$data["result"]=$results;
		//$data["sql"]=var_export($results,true);
	}
	else{
		$data["output"]="hihi";
	}
	sendjsondata();
}

if(isset($_GET['token']) && $_GET['token']=="firstpage")
{
	$srnid=-1;
	$data["data"][++$srnid]["title"]="Instruction";
	$data["data"][$srnid]["body"][0]="<center><strong>NON-DISCLOSURE AGREEMENT AND GENERAL TERMS OF USE FOR ENTRANCE TEST</strong></center><br/>";
	$data["data"][$srnid]["body"][1]="This test is confidential and proprietary. We expressly prohibit you from disclosing, publishing, reproducing, or transmitting this test, in whole or in part, in any form or by any means, verbal or written, electronic or mechanical and for any purpose.";
	$data["data"][$srnid]["body"][2]="We assume those student who are taking the test  are interested to enroll in our training program and are allowing us to contact them for enrollment in training and other evaluation formalities if they qualify in our ENTRANCE TEST";
	$data["data"][$srnid]["tail"]="By clicking on continue button you agree to our terms of use for ENTRANCE TEST	";
		
	
	$data["data"][++$srnid]["title"]="Instruction";
	
	$data["data"][$srnid]["body"]=array();
	$data["data"][$srnid]["body"][]="<center><strong>INSTRUCTIONS AND GENERAL GUIDELINES FOR  ENTRANCE TEST</strong></center><br/>";
	$data["data"][$srnid]["body"][]="All Questions carries equal marks and there is no negative marking but there is minimum passing marks applicable for qualifying some sections or set of questions therefore answers every questions correctly.";
	$data["data"][$srnid]["body"][]="You can leave a question unanswered (incomplete ) , you can mark answered and unanswered questions if you are not satisfied with your answer choice and can review/attempt it later if you have time remaining.";
	$data["data"][$srnid]["body"][]="Our scoring process does not differentiate between marked and unmarked questions and all answered question (marked or unmarked) will be accounted to arrive at your final score.";
	$data["data"][$srnid]["body"][]="Total Questions , Qualifying percentage and Time duration applicable for the test will be notified to you in the subsequent screens of test.";
	$data["data"][$srnid]["body"][]="If you leave the test in between or close the test page window without completing the test , you will not be able to take the test again.";
	//$data["data"][$srnid]["body"][]=
	$data["data"][$srnid]["tail"]="Wish You All The Best";
	
	$sql="select * from es_prog where prog_id =$progid";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	$scrntitle = "ENTRANCE TEST  for ".$row["prog_name"];
	$data["data"][++$srnid]["title"]=$scrntitle;
	$data["data"][$srnid]["body"][0]=$row["prog_desc"];
	$data["data"][$srnid]["body"][1]="Qualifing percentage for this program is ".$row["prog_test_qualifing_percent"]."%.";
	$data["data"][$srnid]["tail"]="";
	
	$testid=$row["prog_test_id"];
	
	$sql="select * from es_test where test_id =$testid";
	$res = mysql_query($sql);
	/*$er=mysql_error();
	if($er!="") send_error($er);*/
	
	$row = mysql_fetch_array($res);
	$data["data"][++$srnid]["title"]=$scrntitle;//"Test: ".$row["test_name"]." (".$row["test_code"].")";
	$data["data"][$srnid]["body"][0]=$row["test_description"];
	$data["data"][$srnid]["body"][1]="Total time for the ENTRANCE TEST– ".$row["test_duration"] . " minutes";
	$data["data"][$srnid]["body"][2]="Total Questions in the ENTRANCE TEST - ".$row["test_totalquestion"];
	$data["data"][$srnid]["tail"]="";
	sendjsondata();
}

//$testid=1;
$sql="select prog_test_id from es_prog where prog_id =$progid";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$testid=$row["prog_test_id"];

if(isset($_GET['token']) && $_GET['token']=="loadmodule"){
	$sql="select mod_id, mod_name, mod_desc, mod_duration, mod_optional from es_module where mod_test_id = $testid";
	$res = mysql_query($sql);
	$er=mysql_error();
	$count=0;
	
	while($row = mysql_fetch_row($res)){
		$data["module"][$count]=$row;
		$data["module"][$count][0]=$row[0]+$modindx;
		$sql2="select sub_id, sub_name, ms_no_qsn from es_sub, es_mod_sub where ms_sub_id=sub_id and ms_mod_id=".$row[0];
		$ressub = mysql_query($sql2);
		$subcnt=0;
		while($rows = mysql_fetch_row($ressub)){
			$data["module"][$count]["subject"][$subcnt]=$rows;
			$subcnt++;
		}
		$count++;
	}
	sendjsondata();
}

if(isset($_GET['token']) && $_GET['token']=="answers")
{
	//print_r($_POST);
	$data["success"]="YES";
	if(isset($_POST['ansid']))
	{
		foreach($_POST['ansid'] as $indx=>$val)
		{
			$sqlupdate="";
			if($_POST['ansans'][$indx]=="")
				$sqlupdate="update es_ans set  ans_status='".$_POST['ansstat'][$indx]."', ans_ans='none' where ans_id='".$_POST['ansid'][$indx]."'";
			else
				$sqlupdate="update es_ans set ans_status='".$_POST['ansstat'][$indx]."', ans_ans='".$_POST['ansans'][$indx]."' where ans_id='".$_POST['ansid'][$indx]."'";
		}
		if(!mysql_query($sqlupdate))
		{
			$data["success"]="NO";
		}
		$sqlupdate="update es_std_prog set  sp_test_last_updated=\"".date("Y-m-d H:i:s")."\" where sp_id=".$spid;
		
		if(!mysql_query($sqlupdate))
		{
			$data["success"]="NO";
		}
	}
	sendjsondata();
	
}

if(isset($_GET['token']) && $_GET['token']=="post")
{
	if(isset($_SESSION["examstarted"]))
	{
		$data["error"]="One test already running...\nYou can't start another one, until you have finished current one";
		sendjsondata();		
	}
	//$data["post"]=$_POST;
	$sub=null;
	if(isset($_POST["selectedsub"])){
		foreach($_POST["selectedsub"] as $value){
			$tmp=explode("~", $value, 2);
			$sub[$tmp[0]]=$tmp[1];
		}
	}
	//$spid=1;
	$addspsql="update es_std_prog set sp_test_start_time=\"".date("Y-m-d H:i:s")."\", sp_status=\"inprogress\" where sp_id=".$spid;
	//echo $addspsql;
	/*****************************************************************
	 *   uncoment bellow to make it workable
	 *****************************************************************/
	
	$res=mysql_query($addspsql);
	#$row=mysql_fetch_array($res);
	$er=mysql_error();
	if($er!="") send_error($er);
	/*$spid=$row[0];/**/
	
	//if(isset($_POST["selectedsub"]))
	{
		$sql="select mod_id, mod_name, mod_desc, mod_duration, mod_optional from es_module where mod_test_id = $testid";
		$res = mysql_query($sql);
		$er=mysql_error();
		$count=0;
		$qsnno=1;
		$values=null;
		while($row = mysql_fetch_row($res)){
			$data["module"][$count]=$row;
			$data["module"][$count][0]=$row[0]+$modindx;
			
			$sql2="select sub_id, sub_code, ms_no_qsn from es_sub, es_mod_sub where ms_sub_id=sub_id and ms_mod_id=".$row[0];
			if(isset($sub[$row[0]+$modindx]))
			{
				$sql2.=" and sub_id=".$sub[$row[0]+$modindx];
			}
			$ressub = mysql_query($sql2);
			$subcnt=0;

			while($rows = mysql_fetch_array($ressub)){
				$data["module"][$count]["subject"][$subcnt][0]=$rows[0];
				$data["module"][$count]["subject"][$subcnt][1]=$rows[1];
				$sqlqsnid="select qsn_id from es_qsn where qsn_sub_id='".$rows['sub_id']."' order by rand() limit ".$rows['ms_no_qsn'];
				$qnsids=null;
				$qsncount=0;
				if($resqsnid=mysql_query($sqlqsnid)){
					while($qsnid=mysql_fetch_array($resqsnid))
					{
						$qsnids[$qsncount]=$qsnid[0];
						$qsncount++;
					}
				}
				//shuffle($qsnids);
				//$qsnids=array_slice($qsnids,0,$rows['ms_no_qsn']);
				//print_r($qsnids);
				foreach($qsnids as $qsnid)
				{
					$values[$qsnno]="('$qsnid', '$spid', '".$row[0]."','$qsnno')";
					$qsnno++;
				}
				$subcnt++;
			}
			
			//
			$count++;
		}
		$addqsn ="insert into es_ans (ans_qsn_id, ans_sp_id, ans_mod_id, ans_qsn_no) values ";
		$addqsn.=implode(", ", $values);
		//echo $addqsn;
		$data["success"]="NO";
		if(mysql_query($addqsn))
		{
			$data["success"]="YES";
		}
		
	}
	if ($spid==0)
	{
		$data["error"]="spid";
	}
	$data["spid"]=$spid;
	$_SESSION["examstarted"]=$progid.":".$studid;
	sendjsondata();
}


if(isset($_GET['token']) && $_GET['token']=="question"){
	
	//$spid=$_GET['spid'];
	$modid=$_GET["modid"]-$modindx;
	$sql="SELECT ans_id, ans_qsn_no, qsn_id, 
		qsn_qsn, qsn_img, 
		qsn_opt_a, qsn_img_a, 
		qsn_opt_b, qsn_img_b, 
		qsn_opt_c, qsn_img_c, 
		qsn_opt_d, qsn_img_d, ans_status 
	   FROM es_ans AS t1 INNER JOIN es_qsn AS t2 ON t1.ans_qsn_id = t2.qsn_id where ans_sp_id='$spid' and ans_mod_id='$modid'";
	//echo $sql;
	$resqsn=mysql_query($sql);
	$er=mysql_error();
	if($er!="") send_error($er);
	//echo $sql."<br>";
	$count=0;
	while($row=mysql_fetch_array($resqsn))
	{
		
		$data["qsn"][$count]["id"]=$row['ans_id'];
		$data["qsn"][$count]["qsnno"]=$count+1;//$row['ans_qsn_no'];
	    $data["qsn"][$count]["qsnid"]=$row['qsn_id'];
	    $data["qsn"][$count]["qeustion"]=$row['qsn_qsn'];
	    $data["qsn"][$count]["qimage"]=textimage($row['qsn_img']);
	    $data["qsn"][$count]["option"]["a"]=$row['qsn_opt_a'];
		$data["qsn"][$count]["option"]["b"]=$row['qsn_opt_b'];
		$data["qsn"][$count]["option"]["c"]=$row['qsn_opt_c'];
		$data["qsn"][$count]["option"]["d"]=$row['qsn_opt_d'];

		$data["qsn"][$count]["img"]["a"]=textimage($row['qsn_img_a']);
		$data["qsn"][$count]["img"]["b"]=textimage($row['qsn_img_b']);
		$data["qsn"][$count]["img"]["c"]=textimage($row['qsn_img_c']);
		$data["qsn"][$count]["img"]["d"]=textimage($row['qsn_img_d']);
		
		$data["qsn"][$count]["status"]=$row['ans_status'];
		$count++;
	}
	$data["qsncount"]=$count;
	//print_r($_GET);
	//sleep(2);
	sendjsondata();
	//endpage();
}
unset($_SESSION["examstarted"]);
if(isset($_SESSION["examstarted"]))
{
	//echo "jkd";
	redirect("myprograms.php");
}
?>
<?php include('includes/upper.php'); ?>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript">
progid=<?php echo ($progid+$prgindx) ?>

spid=<?php echo $spid ?>

//qsnno, id, qsnid, qeustion, qimage, opval, opimg, ansstat, ""
//  0     1   2         3        4      5      6       7      8
function loadData(token,calbak)
{
	$.ajax({
		type: "GET",
		url: "question.php?spid="+spid+"&prgid="+progid+"&token="+token,
		//dataType: "xml",
		//dataType: "text",
		dataType: "json",
		success: function(txt) {
			//alert(txt);return;
			if(iserror(txt))
			{
				return;
			}
			calbak(txt);			
		},
		error: function(jqXHR, textStatus, errorThrown){
			alertdia("Error in loading. "+"question.php?spid="+spid+"&prgid="+progid+"&token="+token);
			//alertdia("Error in loading. ")
		}
	});
}

function postData(data,calbak)
{
	$.ajax({
		type: "POST",
		url: "question.php?spid="+spid+"&prgid="+progid+"&token=post",
		//dataType: "xml",
		//dataType: "text",
		dataType: "json",
		data: data,
		success: function(txt) {
			//diaclose();alert(txt);return;
			if(iserror(txt))
			{
				return;
			}
			calbak(txt);			
		},
		error: function(jqXHR, textStatus, errorThrown){
			alertdia("Error in loading. "+"question.php?spid="+spid+"&prgid="+progid+"&token=post");
			//alertdia("Error in loading.")
		}
	});
}



/******************************************************************
 *  First frame
 ******************************************************************/
fstfrmcount=0;
fstfrmcntnt=null;
ffcurid=0;
//alert("12");
$(function(){
	//alert("12");
	diaopen("Loading..","Please wait until we load your first page");
	loadData("firstpage",function(data){
		fstfrmcntnt=data.data;
		fstfrmcount=fstfrmcntnt.length
		//alert(fstfrmcount)
		prv=$("<input />")
		.attr("type","button")
		.val("Back")
		.button()
		.click(function(){
			ffrender(ffcurid-1)
		})		
		
		nxt=$("<input />")
		.attr("type","button")
		.val("Continue")
		.button()
		.click(function(){
			ffrender(ffcurid+1)
		})
		$("#questionbottons")
		.append(prv)
		.append(nxt)
		
		ffrender(0);
		diaclose();
	})
})

function ffrender(nexid){
	if(nexid < 0)
		window.open("myprograms.php","_self")
	else if(nexid >= fstfrmcount){
		diaopen("Loading..","Wait till we load module information..")
		loadData("loadmodule",function(data){
			modules=data.module;
			displaymodule()
			diaclose()
		})
	}
	else{
		$("#questiontitle").text(fstfrmcntnt[nexid].title)
		$("#questionbody").html(fstfrmcntnt[nexid]["body"][0])
		if(fstfrmcntnt[nexid]["body"].length>1)
		{
			lst=$("<ul></ul>")
			.css("padding-left","15px")
			for(itr=1;itr < fstfrmcntnt[nexid]["body"].length;itr++)
			{
				itm=$("<li></li>")
				.html(fstfrmcntnt[nexid]["body"][itr])
				lst.append(itm)
			}
			$("#questionbody").append(lst)
			
			lst=$("<div></div>")
			.html(fstfrmcntnt[nexid]["tail"])
			
			$("#questionbody").append($("<br>"))
			.append(lst)
		}
		ffcurid=nexid;
	}
}

/******************************************************************
 *  modules
 ******************************************************************/
modules=null;
//spid=0;
function displaymodule(){
	//alert(modules)
	try{
		$("#questionbottons").empty();
		$("#questiontitle").text("Modules")
		tbl=$("<table></table>")
		.attr("width","100%")
		.attr("align","center")
		.addClass("questiontable")
		tr=$("<tr></tr>")
		.append("<th>Name</th>")
		.append("<th>Description</th>")
		.append("<th>Duration in Minutes</th>")
		.append("<th>Type</th>")
		.append("<th>Subject(s)</th>")
		tbl.append(tr)
		
		for(i in modules)
		{
			tr=$("<tr></tr>")
			for(j=1; j<4;j++)// in modules[i])
			{
				//alert(j);
				td=$("<td></td>")
				.text(modules[i][j])
				.addClass("questiontable")
				tr.append(td)
			}

			td=$("<td></td>")
			.addClass("questiontable")
			tr.append(td)
			if(modules[i][j]==1)
				td.html("Optional")
			else
				td.text("Compulsory")
			
			if(modules[i][j]==1){
				
				selsb=$("<select></select")
				.attr("name","selectedsub[]")
				.addClass("selectsub")
				for(x in modules[i]["subject"])
				{
					op=$("<option></option>")
					.val(modules[i][0]+"~"+modules[i]["subject"][x][0])
					.text(modules[i]["subject"][x][1])
					selsb.append(op)
				}
				submd = $("<span></span>")
				.append(selsb)
				.append("<br /><blink><font color='blue'>You have to select one subject</font></blink>")
			}
			else{
				submd=$("<span></span>")
				for(x in modules[i]["subject"])
				{
					spn=$("<div></div>").append(modules[i]["subject"][x][1])
					submd.append(spn)
				}
			}
			
			td=$("<td></td>")
			.addClass("questiontable")
			.attr("align", "center")
			.append(submd)
			tr.append(td)
			tbl.append(tr)
		}
		$("#questionbody").empty()
		.append(tbl)
		.append("<br><br><div>Are you prepared to appear in ENTRANCE TEST now ? If you click <strong>YES</strong> your test set will be loaded and you have to take test now and  you can not reappear in the test  again . If you click <strong>NO</strong> and leave the test  you will be able to appear for the test any time before the end date applicable for the ENTRANCE TEST</div>")
		//alert("da");
		prv=$("<input />")
			.attr("type","button")
			.val("No")
			.button()
			.click(function(){
				window.open("myprograms.php","_self")
			})		
			
			nxt=$("<input />")
			.attr("type","button")
			.val("Yes")
			.button()
			.click(function(){
				diaopen("Loading..","Wait till we assemble your question set..")
				datas=$("select.selectsub").serialize()
				window.onbeforeunload=function(){return false}
				//document.onunload= update_finish(false);
				

				postData(datas,function(data){
					//alertdia("posted successfully");
					modules=data.module;
					//spid=data.spid;
					//alert(spid)
					alertdia("Question Assembled Successfully",function(){
						$(window).unload(function() {
							//alert("unload")
							update_finish(false)
						})
						loadqsn(0)
						postAns();
					})
				})
			})
			$("#questionbottons")
			.append(prv)
			.append(nxt)
		}catch(e){alert(e)}
}

noofqns=0;
maxduration=0;

changes=0;

qsn=new Array();
qsnlist=new Array();

function preload(arrayOfImages) {
    $(arrayOfImages).each(function(){
        //$('<img/>')[0].src = this;
        // Alternatively you could use:
         (new Image()).src = this;
    });
}


function loadqsn(modid)
{
	diaclose();
	//alert(modid)
	token="question&modid="+modules[modid][0]+"&spid="+spid;
	//alert(token)
	qsnlist=null
	qsn=new Array();
	qsnlist=new Array();
	diaopen("Loading..", "Loading question of "+modules[modid][1])
	loadData(token,function(data){
		try{
			//alert(data	)
			//$(xml).find('qsn').each(function(index){
			for(x in data.qsn){
				id=data.qsn[x].id
				qsnno=data.qsn[x].qsnno
				qsnid=data.qsn[x].qsnid
				qeustion=data.qsn[x].qeustion
				//qimage=$(data.qsn[x].qimage)
				qimage=data.qsn[x].qimage
				opval=[data.qsn[x].option.a,data.qsn[x].option.b,data.qsn[x].option.c,data.qsn[x].option.d]
				//opimg=[$(data.qsn[x].img.a),$(data.qsn[x].img.b),$(data.qsn[x].img.c),$(data.qsn[x].img.d)]
				opimg=[data.qsn[x].img.a,data.qsn[x].img.b,data.qsn[x].img.c,data.qsn[x].img.d]
				
				preload([$(data.qsn[x].img.a).attr("src"),$(data.qsn[x].img.b).attr("src"),$(data.qsn[x].img.c).attr("src"),$(data.qsn[x].img.d).attr("src"),$(data.qsn[x].qimage).attr("src")])
				
				ansstat=data.qsn[x].status
				qsn[x]=[qsnno, id, qsnid, qeustion, qimage, opval, opimg, ansstat,""]
				//alert(qsn[id])
				if(ansstat!='answered')
				qsnlist.push(x);
			
			}
			incomlist=null
			reviewlist=null
			fulllist=null
			noofqsn=parseInt(data.qsncount)
			//alert(noofqsn)
			//alert(qsn)
			
			//alert(opimg)
			setTimeout(function(){renderqsn(0); diaclose();timer(modid);}, 3000) // time Between two module
			//timer(modid)
			//diaclose();
		}catch(e){ alert(e+" x"+x) }
	})
	
}
var oldTime=null;
var remainin=null;
function timer(modid)
{
	$("#time").text(modules[modid][3]+" Min")
	oldTime=new Date();
	//remaining=new Date();
	//remaining=10*1000//
	remaining=parseInt(modules[modid][3])*60*1000
	startTime(function(){
		if((modid+1)<modules.length)
		{
			loadqsn(modid+1)
		}
		else{
			alertdia("You have finished the test thanking you..",function(){
				update_finish(true)
			})
			$("#time").empty()
			$("#questiontitle").empty()
			$("#questionbody").empty()
			$("#questionbottons").empty()
			$("#questiontitle").text("Wait please..")
			//$("#time").empty()
			$("#questionbody").html("<center>Wait please..<br />We are generating your result</center>")
			
		}
	})
}

function startTime(fnc)
{
	curTime=new Date();
	//alert("here")
	diffTime=curTime.getTime()-oldTime.getTime();
	oldTime=curTime
	
	//if(diffTime>1000)
	try{
		remaining=remaining-diffTime
		seconds=parseInt(remaining/1000);
		hour=parseInt(seconds/3600);
		seconds=seconds%3600
		minute=parseInt(seconds/60)
		second=seconds%60;
		minute=checkTime(minute);
		second=checkTime(second);
		$("#time").text(hour+":"+minute+":"+second)
		//alertdia(diffTime)
		if(remaining<0)
		{
			fnc()
			return;
		}
	}catch(e){alert(e)}
	// add a zero in front of numbers<10
	setTimeout(function(){startTime(fnc)},500);
}
function checkTime(i)
{
	if (i<10)
	{
		i="0" + i;
	}
return i;
}


function renderqsn(index){
	no=qsnlist[index]
	try{
		$("#questiontitle").html("Qsn. "+(qsn[no][0])+"/"+noofqsn)
		//$("#time").html(maxduration+" Min")
		
		body=$("<table></table>")
		.attr("width","800")
		.attr("border","0")
		.attr("cellpadding","15")
		.attr("cellspacing","20")
		
		tbl=$("<tbody></tbody>")
		
		td=$("<td></td>")
		.attr("width","700")
		.attr("valign","top")
		.attr("height","48")
		.text(qsn[no][3])
		.append(qsn[no][4])  // you have to append image here
		//alert(qsn[no][4])
		
		tr=$("<tr></tr>")
		.append(td)
		tbl.append(tr)
		//Option A
		radioimg="<img class='qsnopt' src='images/uncheck.png' value='a' name='"+no+"' \>";
		optiondata=$("<span></span>")
		.text(qsn[no][5][0])
		.append(qsn[no][6][0])
		td=$("<td></td>")
		.attr("bgcolor","#E7E9FA")
		.attr("valign","top")
		//.attr("height","48")
		.click(function(){mngopt('a')})
		.append(radioimg)
		.append(optiondata)
		
		tr=$("<tr></tr>")
		.append(td)
		tbl.append(tr)
		
		
		
		//Option B		
		radioimg="<img class='qsnopt' src='images/uncheck.png' value='b' name='"+no+"' \>";
		optiondata=$("<span></span>")
		.text(qsn[no][5][1])
		.append(qsn[no][6][1])
		td=$("<td></td>")
		.attr("bgcolor","#E7E9FA")
		.attr("valign","top")
		//.attr("height","48")
		.click(function(){mngopt('b')})
		.append(radioimg)
		.append(optiondata)
		
		tr=$("<tr></tr>")
		.append(td)
		tbl.append(tr)
		
			
		//Option C
		radioimg="<img class='qsnopt' src='images/uncheck.png' value='c' name='"+no+"' \>";
		optiondata=$("<span></span>")
		.text(qsn[no][5][2])
		.append(qsn[no][6][2])
		td=$("<td></td>")
		.attr("bgcolor","#E7E9FA")
		.attr("valign","top")
		//.attr("height","48")
		.click(function(){mngopt('c')})
		.append(radioimg)
		.append(optiondata)
		
		tr=$("<tr></tr>")
		.append(td)
		tbl.append(tr)	
		
		
		//option D
		radioimg="<img class='qsnopt' src='images/uncheck.png' value='d' name='"+no+"' \>";
		optiondata=$("<span></span>")
		.text(qsn[no][5][3])
		.append(qsn[no][6][3])
		td=$("<td></td>")
		.attr("bgcolor","#E7E9FA")
		.attr("valign","top")
		//.attr("height","48")
		.click(function(){mngopt('d')})
		.append(radioimg)
		.append(optiondata)
		
		tr=$("<tr></tr>")
		.append(td)
		tbl.append(tr)
	
		body.append(tbl)
		$("#questionbody").html(body)
		$("#questionbottons").empty()
		
		qsnbtn  ="<table width='100%' align=center border=0><tr><td align='left' width='20%'>"
		if(index>0)
		{
			qsnbtn  +="<button class='qsnbtprv qsnpad' attr='prevqsn' next="+(index-1)+">Previous</button>"
		}
		qsnbtn +="</td><td align='center' width='25%'>"
		
		qsnbtn +="<button id='qsnmarkbtn' attr='qsnmark' next="+(index)+">"
		if(qsn[no][7]!='reviewed')
			qsnbtn +="Mark"
		else
			qsnbtn +="Unmark"
		
		qsnbtn +="</button></td><td align='center' width='10%'></td>"
		
		
		qsnbtn +="<td align='center' width='25%'><button class='qsnpad' attr='review'>Review</button></td>"
		qsnbtn +="<td align='right' width='20%' >"
		//p=parseInt(no) < parseInt(qsnlist.length-1); alert(parseInt(qsnlist.length)+" "+p+" "+parseInt(qsnlist.length-1)+" "+parseInt(no))
		if(index<(qsnlist.length-1)) 
		{
			qsnbtn +="<button class='qsnbtnxt qsnpad' attr='nextqsn' next="+(index+1)+">Next</button>"
		}
		qsnbtn +="</td></tr></table>"
		$("#questionbottons").append(qsnbtn)
		
		$("img.qsnopt").each(function(){
			if($(this).attr("value")==qsn[no][8])
			{
				$(this).attr("src","images/check.png")
			}
		})
		renderbutton()
	}catch(e){alert(e)}
}


incomlist=null
reviewlist=null
fulllist=null

function renderReview()
{
	//alert("Hello");
	try{
	incomlist=new Array()
	reviewlist=new Array()
	fulllist=new Array()
	rcont=0;
	icont=0;
	fcont=0;
	$("#questiontitle").html("REVIEW")
	body  = "<table width='98%' align='center' class='questiontable'>"
	body += "<tr>"
	body += "	<th class='questiontable'>Question</th>"
	body += "	<th class='questiontable'>Marked</th>"
	body += "	<th class='questiontable'>Incomplete</th>"
	body += "	<th class='questiontable'>complete</th>"
	body += "</tr>"
	for(i=0; i<noofqsn; i++){
		body += "<tr class='dbclickable' attr='"+i+"'>"
		body += "<td class='questiontable'>"
		body +="Question No."+qsn[i][0]
		body +="</td><td class='questiontable'>"
		if(qsn[i][7]=='reviewed'){
			 body += "Yes"
			 reviewlist[rcont]=i
			 rcont++
			 //alert(i)
		}
		body += "</td><td class='questiontable'>"
		if(qsn[i][8]=="") {
			body += "Yes"
			incomlist[icont]=i
			icont++;
		}
		body += "</td><td class='questiontable'>"
		if(qsn[i][8]!="") body += "Yes"
		body += "</td>"
		body += "</tr>"
		fulllist[fcont]=i
		fcont++
	}
	body += "</table>"
	$("#questionbody").html(body)
	qsnbtn  ="<table width='100%' align=center border=0><tr>"
	if(reviewlist.length>0)
		qsnbtn +="<td align='center'><button class='qsnpad' attr='reviewlist'>Review Marked</buttun></td>"
	if(incomlist.length>0)
		qsnbtn +="<td align='center'><button class='qsnpad' attr='incomlist'>Review Incomplete</buttun></td>"
	qsnbtn +="</tr></table>"
	$("#questionbottons").empty()
	$("#questionbottons").append(qsnbtn)
	$("tr.dbclickable").hover(function(){
		$(this).css("background-color","#E9E9E4");
	},function(){
		$(this).css("background-color","#fff");
	})
	.dblclick(function(){
		i=parseInt($(this).attr("attr"))
		mngqsn("fulllist",+i)
	})
	renderbutton()
	}catch(e){alert(e+" i="+i)}
}
//qsnno, id, qsnid, qeustion, qimage, opval, opimg, ansstat, ""
//  0     1   2         3        4      5      6       7      8


function mngqsn(command,value)
{
	//alert(command)
	//alert(command+" "+value)
	if(command=="prevqsn" || command=="nextqsn")
	{
		renderqsn(parseInt(value))
	}
	else if(command=='qsnmark'){
		//alert($("#qsnmarkbtn").text())
		if(qsn[qsnlist[value]][7]=='reviewed'){
			
			if(qsn[qsnlist[value]][8]==''){
				qsn[qsnlist[value]][7]='unanswered'
			}else{				
				qsn[qsnlist[value]][7]='answered'
			}
			$("#qsnmarkbtn").button( "option", "label", "Mark")
		} else {
			qsn[qsnlist[value]][7]='reviewed'
			$("#qsnmarkbtn").button( "option", "label","Unmark")
		}
		pushans(qsnlist[value])
	}
	else if(command=="review"){
		renderReview()
	}
	else if(command=="reviewlist"){
		try{
		//alert(reviewlist.length)
		qsnlist=reviewlist
		/*for(i=0;i<reviewlist.length;i++)
			qsnlist.push(reviewlist[i])*/
		//alert(qsnlist+" "+qsnlist.length)
		renderqsn(0)
		}catch(e){alert(e) }
	}
	else if(command=="incomlist"){
		qsnlist=incomlist
		renderqsn(0)
	}
	else if(command=="fulllist")
	{
		qsnlist=fulllist
		renderqsn(value)
	}
	else{
		alert("Invalid Command")
	}
}
function mngopt(id)
{
	$("img.qsnopt").each(function(){
		no=parseInt($(this).attr('name'))
		//alert(qsn[no][8])
		if($(this).attr("value")==id)
		{
			if(qsn[no][8]!=id){
				$(this).attr("src","images/check.png")
				qsn[no][8]=id
				if(qsn[no][7]!="reviewed"){
					qsn[no][7]="answered"
				}
			}else{
				$(this).attr("src","images/uncheck.png")
				qsn[no][8]=""
				if(qsn[no][7]!="reviewed"){
					qsn[no][7]="unanswered"
				}
			}
			pushans(no)
		}
		else{
			$(this).attr("src","images/uncheck.png")
		}
		//changes=1;
	})
	//alert(qsn[no][8])
}

//qsnno, id, qsnid, qeustion, qimage, opval, opimg, ansstat, ""
//  0     1   2         3        4      5      6       7      8
/******************************************************************
 *                   Display report
 ******************************************************************/
 function display_result(result)
 {
 	window.onbeforeunload=null
 	$("#questiontitle").text("Report page")
	$("#time").empty()
	$("#questionbody").empty()
	$("#questionbottons").empty()
	
	tbl=$("<table></table>")
	.attr("class","questiontable")
	.attr("align","center")
	.width("90%")
	tr=$("<tr></tr>")
	td=$("<th></th>").text("Module")//.attr("class","questiontable")
	tr.append(td)
	td=$("<th></th>").text("Total question")//.attr("class","questiontable")
	tr.append(td)
	td=$("<th></th>").text("Attempts")//.attr("class","questiontable")
	tr.append(td)
	td=$("<th></th>").text("Correct")//.attr("class","questiontable")
	tr.append(td)
	td=$("<th></th>").text("Incorrect")//.attr("class","questiontable")
	tr.append(td)
	//td=$("<th></th>").text("Status")//.attr("class","questiontable")
	//tr.append(td)
	tbl.append(tr)
	for(x in result)
	{
		
		if(x!="test")
		{
			tr=$("<tr></tr>")
			td=$("<td></td>").text(result[x]['name']).attr("align","center").attr("class","questiontable")
			tr.append(td)
			td=$("<td></td>").text(result[x]['totqsn']).attr("align","right").attr("class","questiontable")
			tr.append(td)
			td=$("<td></td>").text(result[x]['attemps']).attr("align","right").attr("class","questiontable")
			tr.append(td)
			td=$("<td></td>").text(result[x][1]).attr("align","right").attr("class","questiontable")
			tr.append(td)
			td=$("<td></td>").text(result[x][2]).attr("align","right").attr("class","questiontable")
			tr.append(td)
			//sts=result[x]['passed']==1 ? "<font color=\"blue\">Passed</font>" : "<font color=\"red\">Failed</font>"
			//td=$("<td></td>").html(sts).attr("align","center").attr("class","questiontable")
			tr.append(td)
			tbl.append(tr)	
		}
	} 
	tr=$("<tr></tr>")
	td=$("<td></td>").html("<b>Over all status</b>").attr("align","left").attr("colspan","5").attr("class","questiontable")
	tr.append(td)
	//sts=result["test"]['passed']==1 ? "<font color=\"blue\">Passed</font>" : "<font color=\"red\">Failed</font>"
	//td=$("<td></td>").html("<b>"+sts+"</b>").attr("align","center").attr("class","questiontable")
	tr.append(td)
	tbl.append(tr)	
	$("#questionbody").empty().append(tbl)
	
	tbl=$("<table></table>")
	//.attr("class","questiontable")
	.attr("align","center")
	.width("97%")
	tr=$("<tr></tr>")
	btn=$("<input />").attr("type","button").attr("value","Home").button().click(function(){
		window.open("index.php","_self")
	})
	td=$("<td></td>").append(btn).attr("align","center").width("100%")//.attr("class","questiontable")
	tr.append(td)
	
	//btn=$("<input />").attr("type","button").attr("value","Report").click(function(){
	//	window.open("report.php","_self")
	//}).button()
	//td=$("<td></td>").append(btn).attr("align","center").width("50%")//.attr("class","questiontable")
	//tr.append(td)
	
	tbl.append(tr)
	
	
	$("#questionbottons").empty()
	$("#questionbottons").append(tbl)
 }
 
/******************************************************************
 *                Exam finished
 ******************************************************************/

function update_finish(showreport)
{
	//alert("here1")
	if(sendworking || sendans.length>0)
	{
		setTimeout(function(){update_finish(showreport)},500)
		return
	}
	//alert("here")
	loadData("finished&spid="+spid,function(data){
		try{
			//alert(data.output)
			if(data.done=="NO"){
				setTimeout(function(){update_finish(showreport)},500)
				
				return
			}
			//alert(data.sql+"\nAbhijit");
			if(showreport)
				display_result(data.result)
			//alert("update_finish:hihi");
			//setTimeout(function(){update_finish()},500)
			//window.open("index.php","_self")
		}catch(e){alert(e)}
	})
} 
function display_res(res){
	
}
 
/******************************************************************
 *       Answer Management
 ******************************************************************/
var sendans=new Array()
var sentans=new Array()
var sendworking=false
function pushans(qnsno)
{
	//alert(qsn[qnsno][3])
	sendans.push([qsn[qnsno][1],qsn[qnsno][7],qsn[qnsno][8]])
}

function postAns()
{
	//alert("here")
	try{
		datas= new Array();
		if(sendans==null || sendans.length==0)
		{
			setTimeout(function(){postAns()},500);
			return;
		}
		//alertdia("here");
		while(sendans.length>0){
			p=sendans.pop()
			sentans.push(p)
			datas.push({name:"ansid[]",value:p[0]})
			datas.push({name:"ansstat[]",value:p[1]})
			datas.push({name:"ansans[]",value:p[2]})
		}
		sendworking=true
		$.ajax({
			type: "POST",
			url: "question.php?spid="+spid+"&prgid="+progid+"&token=answers",
			//dataType: "xml",
			//dataType: "text",
			dataType: "json",
			data: datas,
			success: function(txt) {
				//diaclose();alert(txt);return;
				if(iserror(txt) || txt.success!="YES")
				{
					//return;
					while(sentans.length>0){
						p=sentans.pop()
						sendans.push(p)
					}
				}
				//sentans=new Array()
				//alertdia(txt)
				setTimeout(function(){postAns()},500)
				sendworking=false
				//calbak(txt);			
			},
			error: function(jqXHR, textStatus, errorThrown){
				alertdia("Error in loading. "+"question.php?spid="+spid+"&prgid="+progid+"&token=post");
				while(sentans.length>0){
					p=sentans.pop()
					sendans.push(p)
				}
				setTimeout(function(){postAns()},500)
				sendworking=false
			}
		});
	}catch(e){alert(e)}
}


function renderbutton() {
	$( "button,", "#questionbottons" ).click(function(){
		//alert($(this).attr("next"))
		mngqsn($(this).attr("attr"),$(this).attr("next"),this)
	})
	.button()
	
	//<img class='qsnopt' src='images/uncheck.png'
	
	
	//$("#questionhead").attr("class", "qsnpad ui-widget-header  ui-corner-all")
	//$( "a", ".demo" ).click(function() { return false; });
}



function iserror(data)
{
	str=data.error;//$(xml).find("error").text()
	//alert(str)
	if(str!="" && str!=null){
		alert(str)
		return true
	}
	if(data.loggedin!="YES")
	{
		alert("You are not logged in...");
		window.onbeforeunload=null;
		window.open("index.php","_self")
	}
	return false
}
</script>
<style type="text/css">
.qsnpad{
	padding:2px;
}
#questionbody{
	margin-left=5px;
	padding:10px;
}
img.qsnopt{height: 20px; width: 20px;}
.questiontable {border:1px solid black; border-collapse:collapse; margin-top:20px}
</style>

<?php include('includes/middle.php'); ?>
<div id="questionContent" class="pad">
	<div id="questionhead" class="ui-widget-header" style="padding-left:5px;"><span id="questiontitle">Wait screen</span><span id="time" style="float:right"></span></div>
	<div id="questionbody" class="insidecontent">
	Page is loading.. wait please
	</div>
	<div id="questionbottons">
	</div>
</div>
<?php include('includes/lower.php'); ?>
