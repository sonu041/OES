<div id="banner">
	<div id="logologin">
	<div id="logo">
	<img src="<?php echo $rootpath; ?>./images/oes.gif" alt="Entrance Test System" height="80" width="400" />
	</div>

	<div id="login">
		<?php
		if($loginfo->isLoggedIn())
		{?>
		<div id="welcome" style="color: black;" >
		Welcome <?php echo $loginfo->getUserName(); ?>
		</div>
		<?php } else {?>
		<div id="login-form" title="User Login">

			<form id="logmein" method="post" action="login.php">
			<fieldset>
				<label class="login" for="username">User Name:</label>
				<input class="login text ui-widget-content ui-corner-all"  type="text" name="username" id="username" value=""/>
				<label class="login" for="password">Password</label>
				<input class="login text ui-widget-content ui-corner-all" type="password" name="password" id="password" value=""/>
			</fieldset>
			</form>
			<!--<a role="button" href="register.php"><p>New User</p></a>
			<a role="button" href="forgetpassword.php"><p>Forget Password</p></a>-->
		</div>
		<?php } ?>
	</div>
	</div> <!--End of logologin-->

	<div id="menu">
	<div id="navigation">
		<div class="left"></div>
		<ul>
		<li ><a href="index.php">Home</a></li>
		<?php		
		if($loginfo->isLoggedIn())
		{?>
		<li> <a href="#">Manage</a>
			<ul>
			<li><a href="submng.php">Subject</a></li>
			<li><a href="tstmng.php">Tests</a></li>
			<li class="last"><a href="prgmng.php">Programs</a></li>
			</ul>
		</li>
		<li> <a href="#">Report</a>
			<ul>
			<li><a href="userprofiles.php">Student</a></li>
			<li><a href="result.php">Result</a></li>
			<li><a href="analysis.php?typ=w">Analysis(With Test)</a></li>
			<li><a href="analysis.php?typ=wo">Analysis(Without Test)</a></li>
			</ul>
		</li>
		<li ><a href="#" id="logout-user">Logout</a></li>
		<?php } else { ?>
		<li ><a href="	#" id="login-user">Login</a></li>
		<?php } ?>
		</ul>
		<div class="right"></div>
	</div>
	</div> <!--End of menu-->
</div>