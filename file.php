<?php
$fylindx=1645;
include('includes/config.php');
$sql="select * from es_prog_file where pf_id=".($_GET["fylid"]-$fylindx);
$res=mysql_query($sql);
$er=mysql_error();
if($er!="")
{
	 echo "No such file found";
}
else{
	if($row=mysql_fetch_array($res)){
		//$width=$row["width"];
		//$height=$row["height"];
		header("Content-Type: ".$row["pf_type"]);
		header("Content-Disposition: attachment; filename=file.".$row["pf_ext"]);
		//header("Content-MD5: ")
		//header("Content-Length: ",$row["pf_size"]);
		echo $row["pf_file"];
	}
}
?>