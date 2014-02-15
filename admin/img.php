<?php
$imgindx=1234;
include('includes/config.php');
$sql="select * from es_image where img_id=".($_GET["token"]-$imgindx);
$res=mysql_query($sql);
$er=mysql_error();
//echo $sql;
//$str="";
if($er!="")
{
	 
}
else{
	if($row=mysql_fetch_array($res)){
		//$width=$row["width"];
		//$height=$row["height"];
		header("Content-Type: ".$row["img_type"]);
		//header("Content-MD5: ")
		//header("Content-Length: ",$row["img_size"]);
		echo $row["img_img"];
	}
}
?>