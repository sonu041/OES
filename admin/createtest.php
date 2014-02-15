<?php 
$rootpath="../";
//chdir($rootpath);
include_once("includes/config.php");
$data["error"]="";
$data["loggedin"]="YES";

if(isset($_GET['token']) && !$loginfo->isLoggedIn())
{
	$data["loggedin"]="NO";
	sendjsondata();
}
if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}
if(isset($_GET['token']) && $_GET['token']=='upload'){
	
	//print_r($_POST); die;
	//test["id"]=$_POST['testid'] > 0 ? $_POST['testid'] : NULL;
	$test["name"]=$_POST['testname'];
	$test["desctiption"]=$_POST['testdesc'];
	$test["duration"]=0;
	$test["code"]=$_POST["testcode"];
	$test["qua"]=$_POST['testqua']; 
	$test["qsn"]=0;
	foreach($_POST["moddura"] as $dur){
		$test["duration"] += $dur;
	}
	$modqsn=NULL;
	foreach($_POST["modid"] as $id=>$moxyz)
	{
		if($_POST["modtypeisany"][$id]==0){
			$modqsn[$id] =0;
			foreach($_POST["modid-".$id."-subqsn"] as $qsn) {
				$modqsn[$id] +=$qsn;
				//echo $qsn." all ";
			}
		}
		else{
			$modqsn[$id] =$_POST["modid-".$id."-subqsn"][0];
			//echo $_POST["modid-".$id."-subqsn"][0]." any ";
		}
		$test["qsn"]+=$modqsn[$id];
	}
	//print_r($_POST);
	//print_r($test); die();
	$addtestsql="select addtest(\"".$test["name"]."\",\"".$test["desctiption"]."\",".$test["duration"].",".$test["qsn"].",\"".$test["code"]."\", \"".$test["qua"]."\")";
	$res=mysql_query($addtestsql);
	$er=mysql_error();
	if($er!="") send_error($er);
	$row = mysql_fetch_array($res);
	mysql_free_result($res);
	$testid=$row[0];
	
	foreach($_POST["modid"]	as $id)
	{
		$addmodulesql="select addmodule($testid, \"".$_POST["modname"][$id]."\", \"".$_POST["moddesc"][$id]."\", ".$_POST["moddura"][$id].",".$_POST['modtypeisany'][$id].",".$_POST['modqua'][$id].")";
		$res=mysql_query($addmodulesql);
		$er=mysql_error();
		if($er!="") send_error($er);
		$row = mysql_fetch_array($res);
		mysql_free_result($res);
		
		$modid=$row[0];
		foreach($_POST["modid-".$id."-subqsn"] as $key => $qsn) {
			$sql="insert into es_mod_sub (ms_mod_id,ms_sub_id,ms_no_qsn)values($modid,\"".$_POST["modid-".$id."-subid"][$key]."\",\"".$qsn."\")";
			mysql_query($sql);
			$er=mysql_error();
			if($er!="") send_error($er);
		
		}
	}
	
	/*header("Content-Type: application/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	endpage();*/
	sendjsondata();
}


if(isset($_GET['token']) && $_GET['token']=='setup') {
	//sleep(1);
	$sql="select * from es_sub";
	$res=mysql_query($sql);
	$str="";
	while($row = mysql_fetch_array($res)){
		/*$str .= "\t<sub>\n";
		$str .= "\t\t<id>".$row[0]."</id>\n";		
		$str .= "\t\t<name>".$row[1]."</name>\n";
		$str .= "\t\t<code>".$row[2]."</code>\n";
		$str .= "\t\t<desc>".$row[3]."</desc>\n";
		$str .= "\t\t<qscont>".$row[4]."</qscont>\n";
		$str .= "\t\t<mark>".$row[5]."</mark>\n";
		$str .= "\t</sub>\n";*/
		$str=NULL;
		$str["id"]=$row[0];		
		$str["name"]=$row[1];
		$str["code"]=$row[2];
		$str["desc"]=$row[3];
		$str["qscont"]=$row[4];
		$str["mark"]=$row[5];
		$data["sub"][$row[0]]=$str;
	}
	//header("Content-Type: application/xml; charset=utf-8");
	/*echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";*/
	/*$str=NULL;
	$str["id"]='"'.$row[0].'"';		
	$str["name"]='"'.$row[1].'"';
	$str["code"]='"'.$row[2].'"';
	$str["desc"]='"'.$row[3].'"';
	$str["qscont"]='"'.$row[4].'"';
	$str["mark"]='"'.$row[5].'"';
	$data["sub"]=$str;*/
	//$data["test"]="Assdas\nsasd";
/*?>
<body>
<?php echo $str ?>
</body>  
<?php*/
sendjsondata();
//endpage();
}	 
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->

<script type="text/javascript" src="<?php echo $rootpath ?>admin/js/createtest.js"></script>
<script type="text/javascript" >


test=[-1,"","","",0]
module=new Array()
//module[0]=["Mod1","desc",1,[[1,10],[3,20]],10,0]
//module[1]=["Mod1","desc",0,[[1,10],[3,20]],10,0]
// name, desc, optional, [[subid,noqsn]],duration, qual
//  0      1     2       3  3.1   3.2       4       5

subject=[]

function render()
{
	rendertest();
	
	rendermod();
	$( "#testqua" ).slider( "option", "max", maxtestqlval());
	$( "#iptestqua" ).text( test[4]);
}

$(/*document).ready(*/function() {
	$("#testhead").attr("class", "tstpad ui-widget-header")	
	
	
	$( "#dialogtest" ).dialog({
		modal: true,
		autoOpen: false,
		closeOnEscape: false,
		beforeClose: function(event, ui) { 
			if($( "#dialogtest" ).attr("attr")=="no")
				return false
			$( "#dialogtest" ).attr( "attr","no" )
			return true 
		}
	});
	$("input:button").button()
	$("#upload").click(function(){
		uploaddata()
	})
	$("#addmodule").click(function(){
		//i=parseInt($(this).attr("mod"))
		//alert(module.length)
		//if(	added==0){
			editmodule(module.length, 1)
			//added=1
		//}
		//enablediaclosebutton()
		return false;
	})
	$("#edittest").click(function(){
		//i=parseInt($(this).attr("mod"))
		//editmodule(module.length)
		addtest(function(){render()})
		enablediaclosebutton()
		return false;
	})
	
	setuppage()
	try{
	$("#iptestqua").text(test[4])
	$("#testqua")
	.slider({
		range: "min",
		value: test[4],
		min: 0,
		max: maxtestqlval(),
		slide: function( event, ui ) {
			//$( "#amount" ).val( "$" + ui.value );
			try{
			id=$(this).attr("id")
			/*i=parseInt($(this).attr("mod"))
			j=parseInt($(this).attr("sub"))*/
			//alert($( "#modqua-"+i ).attr("val"))
			test[4]=parseInt(ui.value)
			$( "#ip"+id ).text( ui.value )
			}catch(e){alert("ip "+e)}
		}
	});	
	}catch(e){	alert("test assembeled "+e) }
});
function maxtestqlval()
{
	x=0
	for(j in module)
	{
		x += maxmodqlval(j)
	}
	return x;
}

function validatemodule()
{
	for( i in module)
	{
		for(j in module[i][3])
		{
			for(k in module[i][3])
			{
				if(j!=k && module[i][3][j][0]==module[i][3][k][0])
				{
					alertdia("Module "+module[i][0]+" contain "+subject[module[i][3][j][0]]+" more than once")
					//alert(i)
					//activeind=i;
					//render();
					return false;
				}
			}
		}
	}
	return true;
}

function uploaddata(){
	
	//["Mod1","desc",0,[[1,10],[3,20]],10]
	if(!validatemodule()) return;
	datas=[ ]
	datas.push({name:"testid",value: test[0]})
	datas.push({name: "testname",value: test[1]})
	datas.push({name:"testdesc", value: test[2]})
	datas.push({name:"testcode", value: test[3]})
	datas.push({name:"testqua", value: test[4]})
	for (i in module) {
		datas.push({name:"modid[]",value:i})
		datas.push({name:"modname[]",value:module[i][0]})
		datas.push({name:"moddesc[]",value:module[i][1]})
		datas.push({name:"modtypeisany[]",value:module[i][2]})
		datas.push({name:"moddura[]",value:module[i][4]})
		datas.push({name:"modqua[]",value:module[i][5]})
		for( j in module[i][3]){
			datas.push({name:"modid-"+i+"-subid[]",value:subject[module[i][3][j][0]][0]})
			datas.push({name:"modid-"+i+"-subqsn[]",value:module[i][3][j][1]})
		}
	}
	diaopen("Loading...","Loading...")
	$.ajax({
		type: "POST",
		url: "createtest.php?token=upload&rand="+Math.random(),
		data:datas,
		//dataType: "xml",
		//dataType: "text",
		dataType: "json",
		success: function(txt) {
			//diaclose();alert(txt); return;
			//alertdia(txt); return;
			if(iserror(txt))
			{
				return;
			}
			window.onbeforeunload=null;
			window.open("tstmng.php","_self")
		},
		error: function(){
			alertdia("Error in uploading try again later");
		}
	});
}
window.onbeforeunload=function(){return "You are sure?"}

function getdata(jsob)
{
	/*$(xml).find("sub").each(function(){
		id=parseInt($(this).find('id').text())
		nme=($(this).find('name').text())
		code=($(this).find('code').text())
		desc=($(this).find('desc').text())
		qscont=parseInt($(this).find('qscont').text())
		mark=parseInt($(this).find('mark').text())
		subject.push([id,nme,code,desc,qscont,mark])
	})*/
	for(i in jsob.sub){
		id=parseInt(jsob.sub[i].id)
		nme=(jsob.sub[i].name)
		code=(jsob.sub[i].code)
		desc=(jsob.sub[i].desc)
		qscont=parseInt(jsob.sub[i].qscont)
		mark=parseInt(jsob.sub[i].mark)
		subject.push([id,nme,code,desc,qscont,mark])
	}
	//alert(jsob.test)
}


function setuppage()
{
	diaopen()
	$.ajax({
		type: "GET",
		url: "createtest.php?token=setup&rand="+Math.random(),
		//dataType: "xml",
		//dataType: "text",
		dataType: "json",
		success: function(txt) {
			//alert(txt); return;
			//alert(txt.sub[1].name); return;
			if(iserror(txt))
			{
				return;
			}
			getdata(txt);
			diaclose()
			
			addtest(function(){
				editmodule(0,2)
				//render()
			},1)
			
			
		}
	});
}


function diaclose(){
	$( "#dialogtest" ).attr( "attr","yes" )
	$( "#dialogtest" ).dialog( "close" )
	//$( "#dialogtest" ).attr( "attr","no" )
	//if(typeof fnc != "function")
		//fnc();
	
}
function diaonclose(func){
	$( "#dialogtest" ).bind( "dialogclose", function(event, ui) {
			func(event, ui)
			$( "#dialogtest" ).bind( "dialogclose", function(event, ui) { });
		});
}
function enablediaclosebutton()
{
	$( "#dialogtest" ).attr( "attr","yes" )
	$(".ui-dialog-titlebar-close").show();
}
function diaopen(title,body,func)
{
	$(".ui-dialog-titlebar-close").hide()
	if(title!=null && title!="")
	{
		$( "#dialogtest" ).dialog( "option", "title",title )
	}
	if(body!=null && body!="")
	{
		$( "#dialogtest" ).html(body)
	}
	if(typeof func=="function")
	{
		$( "#dialogtest" ).bind( "dialogopen", function(event, ui) {
			func(event, ui)
		});
	}
	else{
		$( "#dialogtest" ).bind( "dialogopen", function(event, ui) {
			// func(event, ui)
		});
	}
	$( "#dialogtest" ).dialog( "open" )
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
.tstpad{
	padding:2px;
}
/*.qsnbtnxt{float:right;}
.qsnbtprv{float:left;}*/
img.qsnopt{height: 20px; width: 20px;}
.questiontable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
#demo-frame > div.demo { padding: 10px !important; };
</style>
<?php include('includes/middle.php'); ?>
<?php include('includes/content_creattest.php'); ?>
<?php include('includes/lower.php'); ?>
