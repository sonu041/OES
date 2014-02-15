<div id="not-footer">
<div id="content-background" class="insidecontent">

<div id="program-report"><?php
$sql=$sql="SELECT * FROM es_student where std_isactive='1'";
$res = mysql_query($sql);
?>
<h1>Student profiles</h1>
<hr>
<table class="report-table">
	<tr>
		<th style="width: 50%;">Student Name</th>
		<th>Email</th>
		<th>Contact</th>
		<th>Details</th>
		<!-- <th style="width: 13%;">Status</th> -->
	</tr>
	<tbody>
<?php
while ($row = mysql_fetch_array($res)) {
	?>

		<tr>
			<td class="table-form-contents"><?php echo $row['std_name']; ?></td>
			<td class="table-form-contents"><?php echo $row['std_email_id']; ?></td>
			<td class="table-form-contents"><?php echo $row['std_contact_no']; ?></td>
			<td class="table-form-contents"><a href="profile.php?id=<?php echo $row['std_id']; ?>">Details</a></td>
		</tr>
	
	<?php
}?>
</tbody>
</table>
<br/>
<?php
mysql_free_result($res);
?></div>
</div>
</div>
</div>
