
<div id="not-footer">
<div id="content-background">
<div id="content">
<div id="profile" class="insidecontent">
<h1>Profile</h1>
<hr>
<div class="ember-view">
<div id="ember289" class="ember-view">
<div class="profile-section">

<?php
$sql="SELECT * FROM es_student where std_id = ".$_SESSION['userId'];
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
?>

<div class="profile-section-content">

<table class="table-form">
	<tbody>
		<tr>

			<td class="table-form-title">Full Name</td>
			<td class="table-form-contents"><?php echo $row['std_name']; ?></td>
		</tr>
		<tr>
			<td class="table-form-title">Email ID</td>
			<td class="table-form-contents"><?php echo $row['std_email_id']; ?></span>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Contact No</td>
			<td class="table-form-contents"><?php if ($row['std_contact_no'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_contact_no']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Address</td>
			<td class="table-form-contents"><?php if ($row['std_address'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_address']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">City</td>
			<td class="table-form-contents"><?php if ($row['std_city'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_city']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">State</td>
			<td class="table-form-contents"><?php if ($row['std_state'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_state']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Country</td>
			<td class="table-form-contents"><?php if ($row['std_country'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_country']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Pin Code</td>
			<td class="table-form-contents"><?php if ($row['std_pincode'] == NULL) { ?><span
				class="form-not-set">Not set</span> <?php } else { echo $row['std_pincode']; } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Date of Birth</td>
			<td class="table-form-contents"><?php if ($row['std_dob'] == '0000-00-00') { ?><span
				class="form-not-set">Not set</span> <?php } else { echo date_format(date_create($row['std_dob']), 'jS F Y'); } ?>
			</td>
		</tr>
		<tr>
			<td class="table-form-title"></td>
			<td class="table-form-contents"><a href="profileedit.php">Edit</a>
			</td>
		</tr>
	</tbody>
</table>

<?php
}
mysql_free_result($res);
?>

</div> <!--end of profile-section-content-->
</div> <!--end of profile-section-->

</div> <!--ember289-->
</div> <!--ember-view-->
</div> <!--profile-->
</div> <!--content-->
<!--<div id="program-report">
<h1>Program report</h1>
<hr>
<table class="report-table">
	<tr>
		<th style="width: 50%;">Program Name</th>
		<th>Test taken</th>
		<th>Status</th>

	</tr>
	<tbody>
<?php
$sql="SELECT * FROM es_prog ep, es_std_prog esp where ep.prog_id = esp.sp_prog_id and sp_std_id = ".$_SESSION['userId'];
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
	?>

		<tr>
			<td class="table-form-contents"><?php echo $row['prog_name']; ?></td>
			<td class="table-form-contents"><?php echo $row['sp_test_start_time']; ?></td>
			<td class="table-form-contents"><?php if ($row['sp_is_qualified'] == 1) { echo "PASS"; } else { echo "FAIL"; } ?></td>
		</tr>
	
	<?php
}
mysql_free_result($res);
?>
</tbody>
</table>
<br/>
</div>-->
</div> <!--content-background-->
</div> <!--not-footer-->
