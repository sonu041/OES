<div class="demo">
<div id="changepass" class="insidecontent" style="text-align:center">
<div id="changepass-dialog-form" title="Change Password">
	<p class="validateTips">All form fields are required.</p>

	<form id="changepassword" method="post" action="model/model_changepass.php">
	<fieldset>
		<label for="oldpass">Old Password</label>
		<input type="password" name="oldpass" id="changepass-old" class="text ui-widget-content ui-corner-all" />
		<label for="newpass">New Password</label>
		<input type="password" name="newpass" id="changepass-new" value="" class="text ui-widget-content ui-corner-all" />
		<label for="reenter">Reenter Password</label>
		<input type="password" name="reenter" id="changepass-reenter" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	</form>
</div>


<!--<div id="users-contain" class="ui-widget">
	<!--<h1>Change Password</h1>
</div>-->
<button id="change-pass">Change Password</button>
</div>
</div><!-- End demo -->

