
<div id="not-footer">
<div id="content-background"  class="insidecontent">
<div id="program-report">
<h1>Result report</h1>
<hr>

<?php
$typ=0;

if(isset($_GET['typ']) && ($_GET['typ'] == "w" || $_GET['typ'] == "wo")){
	$typ = ($_GET['typ'] == "w") ? 0:1;
}

$by = 0;
$dir = "ASC";
if(isset($_GET['dir']) && ($_GET['dir'] == "0" || $_GET['dir'] == "1")){
	$dir = ($_GET['dir'] == "0") ? "ASC":"DESC";
}

$analys=Array();

$tbl_nm= Array("es_report_analysis_with_test" , "es_report_analysis_without_test");

$cols = array(array( 
//					'prog_id', 
					'prog_name', // Programe Name
					'test_start', //
					'test_end', 
					'test_taken', 
//					'reg_on', 
					'is_qualified',
					'sp_id',  
//					'test_status', 
//					'std_id', 
					'std_name', 
					'contact_no', 
					'email_id'),
					array( 
//					'sp_id', 
//					'prog_id', 
					'prog_name', 
					'reg_on', 
//					'std_id', 
					'std_name', 
					'contact_no', 
					'email_id')
					);


if(isset($_GET['by']) && isset($cols[$typ][$_GET['by']])){
	$by = $_GET['by'];
}

$clhd = Array(
				"sp_id" => "Subject wise distribution",
				"prog_name" => "Program Name",
				"test_start" => "Entrance Test Open Date",
				"test_end" => "Entrance Test Closing Date",
				"test_taken" => "Test Taken On",
				'is_qualified' => "Pass/Fail Status",
				"reg_on" => "Registration Date",
				"std_name" => "Student Name",
				"contact_no" => "Contact Number",
				"email_id" => "Email"
				);

$header = array();
foreach($cols[$typ] as $key=>$value){
	if(isset($clhd[$value]))
		$header[$value] = array($clhd[$value],$key);
}

$sql = "select ".implode(", ", $cols[$typ])." FROM ". $tbl_nm[$typ];
if(!$typ)
	$sql .= " where test_status = 'over'";

$sql .= " ORDER BY ".$cols[$typ][$by]." ".$dir;


//echo $sql;

$data=array();
if($res = mysql_query($sql)){}
else{
	echo "error";
	end_page();
}

while($row = mysql_fetch_array($res)){
	if($typ) 
		$data[] = $row ;
	else {
		$data[$row['sp_id']] = $row;
		$data[$row['sp_id']]['sp_id'] = Array();
	}
}

if(!$typ){
	$sql = "select sp_id, marks_obtained, max_marks, mod_name from es_module_wise_marks_view";
	if($res = mysql_query($sql)){}
	else{
		echo "error";
		end_page();
	}
	while($row = mysql_fetch_array($res)){
		if(isset($data[$row[0]]))
			$data[$row[0]]['sp_id'][] = $row;
	}
}
?>
</div><br/>
<?php
if(count($data)<=0)
 echo "No data found";
else{
?>
<table class="report-table">
	<tr>
	<?php
	foreach($header as $key=>$value){
		echo "<th><a href=\"";
		echo "analysis.php?typ=";
		echo $typ ? "wo":"w";
		echo "&amp;by=".$value[1];
		if($by==$value[1] && $dir=="ASC")
		echo "&amp;dir=1";
		echo "\">$value[0]</a></th>";
	}
	?>
	</tr>
	<tbody>
<?php

foreach($data as $dt)
{
	echo "<tr>";
	foreach($header as $key => $value){
		echo "<td class=\"table-form-contents\">";
		if($key != 'sp_id')
			echo $dt[$key];
		else {
			foreach($dt[$key] as $p)
				echo $p['mod_name']."-".$p['marks_obtained']."/".$p['max_marks'];
		}
		echo "</td>";
	}
	echo "</tr>";
}

?>
</tbody>
</table>
<?php
}
?>
<br/>
</div>
</div>
</div>
</div>
