
<div id="not-footer">
<div id="content-background"  class="insidecontent">
<div id="program-report">
<h1>Result report</h1>
<hr>
<form name="sort" action="result.php" method="post">
<div align="center">Sort: &nbsp;<select name="myselect">
  <option value=""<?php if(!isset($_POST['myselect'])) { ?> selected="selected"<?php } ?>>Select</option>
  <option value="sp_is_qualified"<?php if(isset($_POST['myselect']) && $_POST['myselect'] == 'sp_is_qualified') { ?> selected="selected"<?php } ?>>Status</option>
  <option value="sp_test_start_time"<?php if(isset($_POST['myselect']) && $_POST['myselect'] == 'sp_test_start_time') { ?> selected="selected"<?php } ?>>Test taken</option>
  <option value="std_name"<?php if(isset($_POST['myselect']) && $_POST['myselect'] == 'std_name') { ?> selected="selected"<?php } ?>>User</option>
  <option value="prog_name"<?php if(isset($_POST['myselect']) && $_POST['myselect'] == 'prog_name') { ?> selected="selected"<?php } ?>>Program Name</option>
</select> 
<input type="checkbox" name="reverse" value="reverse" <?php if(isset($_POST['reverse'])) { ?> checked="true"<?php } ?> /> Reverse Sort
<input type="submit" value="Submit" />
</form>
</div><br/>
<table class="report-table">
	<tr>
		<th>Program Name</th>
		<th>User</th>
		<th>Test taken</th>
		<th>Status</th>
		<!-- <th style="width: 13%;">Status</th> -->
	</tr>
	<tbody>
<?php
$sql="SELECT * FROM es_prog ep, es_std_prog esp, es_student ess where ep.prog_id = esp.sp_prog_id and esp.sp_std_id = ess.std_id";
if(isset($_POST['myselect']) && $_POST['myselect'] != "")
{
   $sql = $sql. " order by ". $_POST['myselect'];
}
if(isset($_POST['reverse']))
{
   $sql = $sql. " desc";
}
//echo $sql;
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
	?>

		<tr>
			<td class="table-form-contents"><?php echo $row['prog_name']; ?></td>
			<td class="table-form-contents"><?php echo $row['std_name']; ?></td>
			<?php if($row['prog_test_isrequired']) { ?>
			<?php if($row['sp_status'] == 'over') { ?>
			<td class="table-form-contents"><?php echo $row['sp_test_start_time']; ?></td>
			<td class="table-form-contents"><?php if ($row['sp_is_qualified'] == 1) { echo "PASS"; } else { echo "FAIL"; } ?></td>
			<?php } elseif($row['sp_status'] == 'inprogress') { ?>
			<td class="table-form-contents">NA</td>
			<td class="table-form-contents">Test is in progress</td>
			<?php } else { ?>
			<td class="table-form-contents">NA</td>
			<td class="table-form-contents">Registered</td>
			<?php } 
				} else { ?>
			<td class="table-form-contents">NA</td>
			<td class="table-form-contents">NA</td>
			<?php } ?>
		</tr>
	
	<?php
}
mysql_free_result($res);
?>
</tbody>
</table>
<br/>
</div>
</div>
</div>
</div>
