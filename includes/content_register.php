<div class="demo">
<div id="register-dialog-form" title="Create new user">
	<p class="validateTips">All form fields are required.</p>

	<form id="registerme" method="post" action="model/model_register.php">
	<fieldset>
		<label for="name">Name</label>
		<input type="text" name="name" id="register-name" class="text ui-widget-content ui-corner-all" />
		<label for="email">Email</label>
		<input type="text" name="email" id="register-email" value="" class="text ui-widget-content ui-corner-all" />
		<label for="contact">Contact No</label>
		<input type="text" name="contact" id="register-contact" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	</form>
</div>
<div id="users-contain" class="ui-widget">
	<h1>New User Registration</h1>
</div>
<button id="create-user">Create new user</button>

</div><!-- End demo -->

