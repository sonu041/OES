
<div id="not-footer">
<div id="content-background">
<div id="content" class="width960 ember-application">
<div id="content">
<div id="profile" class="insidecontent">
<h1>Edit Profile</h1>
<hr>
<div class="ember-view">
<div id="ember289" class="ember-view">
<div class="profile-section"><?php
$sql="SELECT * FROM es_student where std_id = ".$_SESSION['userId'];
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
	?>

<div class="profile-section-content">
<form action="model/model_editprofile.php" method="post" target="profedit-target" id="profEditForm">
<table class="table-form">
	<tbody>
		<tr>

			<td class="table-form-title">Full Name</td>
			<td class="table-form-contents"><input type="text" name="name" value="<?php echo $row['std_name']; ?>"/></td>
		</tr>
		<tr>
			<td class="table-form-title">Email ID</td>
			<td class="table-form-contents"><?php echo $row['std_email_id']; ?></span>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Contact No</td>
			<td class="table-form-contents"><input type="text" name="contact" value="<?php echo $row['std_contact_no']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Address</td>
			<td class="table-form-contents"><input type="text" name="address" value="<?php echo $row['std_address']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">City</td>
			<td class="table-form-contents"><input type="text" name="city" value="<?php echo $row['std_city']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">State</td>
			<td class="table-form-contents"><input type="text" name="state" value="<?php echo $row['std_state']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Coutry</td>
			<td class="table-form-contents"><input type="text" name="country" value="<?php echo $row['std_country']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Pin Code</td>
			<td class="table-form-contents"><input type="text" name="pin" value="<?php echo $row['std_pincode']; ?>"/>
			</td>
		</tr>
		<tr>
			<td class="table-form-title">Date of Birth</td>
			<td class="table-form-contents"><input type="text" name="dob" id="dobValue" readonly="readonly" class='dobDatePicker' value="<?php if ($row['std_dob'] != '0000-00-00') echo date_format(date_create($row['std_dob']), 'Y-m-d'); ?>"/> (yyyy-mm-dd)
			</td>
		</tr>
		<tr>
			<td class="table-form-title"></td>
			<td class="table-form-contents"><input type="submit" value="Submit" id="btnEditSubmit" />
			</td>
		</tr>
	</tbody>
</table>
</form> 
<script type="text/javascript">
function testdate(txtDate, n){
	return isDate(txtDate)
}
/*function updateTips( t ) {
	
}*/
$(function(){
	$("#btnEditSubmit").button()
	$("#profEditForm").submit(function(){
		x=$(this).serializeArray();
		//alert($(this).attr("action"))
		
		c = {
			"name" : [[checkLengthVal, [2,40]]],
			"contact" : [[checkRegexpVal, [/[0-9]{10}/g]]],
			"address" : [[checkLengthVal, [2,40]]],
			"city" : [[checkLengthVal, [2,40]]],
			"state" : [[checkLengthVal, [2,20]]],
			"coutry" : [[checkLengthVal, [2,40]]],
			"pin" : [[checkRegexpVal, [/[0-9]{6}/g]]],
			"dob" : [[testdate, []]]
			
		    }
		lebel = {
			"name" : "Full Name",
			"contact" : "Contact No",
			"address" : "Address",
			"city" : "City",
			"state" : "State",
			"country" : "Country",
			"pin" : "Pin Code",
			"dob" : "Date of Birth"
		        }
		valid = true;
		error=""
		for(i in x){
			args = [x[i].value, "Error"]
			for ( j in c[x[i].name]){
				try{
				arg = args.concat(c[x[i].name][j][1])
				if(!c[x[i].name][j][0].apply(this,arg)){
					valid = false
					error += "!!Error: invalid value for '" + lebel[x[i].name] + "'\n" 
				}
				}catch(e){alert(e)}
			}
		}
		if(!valid)
			alert(error);
		return valid; //confirm("Submit?");
	})
	
	
	/*$('#btnEditSubmit').bind('click', function(){
		var txtVal =  $('#dobValue').val();
		if(!isDate(txtVal))
			alert('Invalid Date of birth');
	});*/
})
</script>

			<iframe name="profedit-target" style="visibility:hidden;position:absolute"></iframe>
	<?php
}
mysql_free_result($res);
?></div>
</div>

</div>
</div>
</div>
</div>

</div>
</div>
</div>
