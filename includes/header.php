<div id="banner">
	<div id="logologin">
	<div id="logo">
	<img src="<?php echo $rootpath; ?>./images/oes.gif" alt="Engineers Technologies" height="80" width="400" />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="http://www.facebook.com/pages/Engineers-Technologies-Empowering-Intellect-to-Drive-Innovation-/308114305965107" target="_blank"><img src="<?php echo $rootpath; ?>./images/facebook.png" alt="fb" height="32" width="32" /></a>
	<a href="https://twitter.com/EngineersTech" target="_blank"><img src="<?php echo $rootpath; ?>./images/twitter.png" alt="tw" height="32" width="32" /></a>
	<a href="http://www.linkedin.com/company/2833074" target="_blank"><img src="<?php echo $rootpath; ?>./images/linkedin.png" alt="ln" height="32" width="32" /></a>
	<!--<a href="https://plus.google.com/EngineersTech" target="_blank"><img src="<?php echo $rootpath; ?>./images/google.png" alt="go" height="32" width="32" /></a>-->
	</div>

	<div id="login">
		
		<ul id="sddm">
		    <li>
		    <a href="#" 
			onmouseover="mopen('m1')" 
			onmouseout="mclosetime()">Welcome <?php echo $loginfo->isLoggedIn() ? $_SESSION['userName'] : "Guest"; ?></a>
			<div id="m1" 
			    onmouseover="mcancelclosetime()" 
			    onmouseout="mclosetime()">
			<?php
			if($loginfo->isLoggedIn())
			{?>
			<a href="profile.php">Profile</a>
			<a href="myprograms.php">My Programs</a>
			<a href="changepwd.php">Change Password</a>
			<a id="logout-user">Logout</a>
			<?php } else { ?>
			<a id="login-user">Login</a>
			<?php } ?>
			</div>
		    </li>
		</ul>
<div style="clear:both"></div>
<style type="text/css">
.hideme {visibility:hidden}
</style>
		<?php if(!$loginfo->isLoggedIn()) {?>
		<div id="login-form" title="User Login" class="hideme">

		<form id="logmein" method="post" action="login.php" target="login-target">
		<fieldset>
			<label class="login" for="email">Email</label>
			<input class="login text ui-widget-content ui-corner-all"  type="text" name="email" id="email" value=""/>
			<label class="login" for="password">Password</label>
			<input class="login text ui-widget-content ui-corner-all" type="password" name="password" id="password" value=""/>
			<input class="redirectto" type="hidden" name="redirectto" value="index.php" />
		</fieldset>
		</form>
		<p><a role="button" id="createuser" href="">New User</a></p>
		<a role="button" id="forgetpassword" href="">Forget Password</a>
			
		<iframe name="login-target" style="visibility:hidden; position:absolute"></iframe>
		</div><!-- -->
		 <div id="register-dialog-form" title="Create new user" class="hideme">
			<p class="register-validateTips">All form fields are required.</p>
			<span class="register_validateTips"></span>
			<form id="registerme" method="post" action="model/model_register.php" target="register-target">
			<fieldset>
				<label for="register-name">Name</label>
				<input type="text" name="name" id="register-name" class="text ui-widget-content ui-corner-all" />
				<label for="register-email">Email</label>
				<input type="text" name="email" id="register-email" value="" class="text ui-widget-content ui-corner-all" />
				<p style="font-size:10px; color:gray;">First time login password will be sent to your email id, please use the same to login and change your password.</p>
				<label for="register-contact">Contact No</label>
				<input type="text" name="contact" id="register-contact" value="" class="text ui-widget-content ui-corner-all" />
				<input class="redirectto" type="hidden" name="redirectto" value="index.php" />
			</fieldset>
			</form>
			<iframe name="register-target" style="visibility:hidden; position:absolute"></iframe>
		</div>  <!-- -->
		<!-- -->
		<div id="forgetpwd-dialog-form" title="Reset you Password" class="hideme">
			<p class="forgetpwd-validateTips">All form fields are required.</p>

			<form id="forgetpwd" method="post" action="model/model_forgetpwd.php" target="forgetpwd-target">
			<fieldset>
				<label for="forgetpwd-email">Email</label>
				<input type="text" name="email" id="forgetpwd-email" value="" class="text ui-widget-content ui-corner-all" />
				<p style="font-size:10px; color:gray;">Your password will be reset and will   be sent to your email id, please use the new password for login and change your password.</p>
				<input class="redirectto" type="hidden" name="redirectto" value="index.php" />
			</fieldset>
			</form>
			<iframe name="forgetpwd-target" style="visibility:hidden; position:absolute"></iframe>
		</div><!-- -->
		<?php } ?>
	</div>
	</div> <!--End of logologin-->

	<div id="menu">
	<div id="navigation">
		<div class="left"></div>
		<ul>
		<li ><a href="index.php">Home</a></li>
		<li ><a href="programlist.php">Programs</a></li>
		<li ><a href="#">Training</a>
			<ul>
			<li><a href="trainingservice.php">Training Services</a></li>
			<li><a href="skillgap.php">Design Skill Gap</a></li>
			<li><a href="mechanicalstream.php">Mechanical Stream</a></li>
			<li><a href="communication.php">Communication Skills Training</a></li>
			<li><a href="framework.php">Training Delivery Framework</a></li>
			<li><a href="howtoregister.php">How to Register</a></li>

			</ul>
		</li>
		<li ><a href="#">About</a>
			<ul>
			<li><a href="about.php">The Company</a></li>
			<li><a href="contactus.php">Contact Us</a></li>
			<li><a href="mentors.php">Associate Mentors</a></li>
			<li><a href="companies.php">Associate Companies</a></li>
			<li><a href="colleges.php">Associate Engg. Colleges</a></li>
			</ul>
			<!--<ul>
			<li><a href="skillgap.php">Skill gap & Design Engineering</a></li>
			</ul>-->
		</li>
		<li ><a href="faq.php">FAQ</a></li>
		<?php		
		if(isset($_SESSION['userId']))
		{?>
		<!--<li ><a href="changepwd.php">Change Password</a></li>
		<li ><a href="#" id="logout-user">Logout</a></li>-->
		<?php } else { ?>
		<!-- <li ><a href="#" id="login-user">Login</a></li> -->
		<?php } ?>
		</ul>
		<div class="right"></div>
	</div>
	</div> <!--End of menu-->
</div>
