<style>
	/*body { font-size: 62.5%; }*/
	label.login, input.login { display:block; }
	input.text { margin-bottom:12px; width:95%; padding: .4em; }
	fieldset { padding:0; border:0; margin-top:25px; }
	h1 { font-size: 1.2em; margin: .6em 0; }
	div#users-contain { width: 350px; margin: 20px 0; }
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script>
<?php if(!$loginfo->isLoggedIn())
		{ ?>
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var email = $( "#email" ),
			password = $( "#password" ),
			allFields = $( [] ).add( email ).add( password ),
			tips = $( ".validateTips" );
	
		$( "#login-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Login": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					if ( bValid ) {
						$("#logmein").submit()
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#login-user" )
			//.button()
			.click(function() {
				$( "#login-form" ).dialog( "open" );
				return false;
			});
	});
	<?php }else{ ?>
	$(function() {
		$( "#logout-user" )
			//.button()
			.click(function() {
				window.open("logout.php",'_self');
				return false;
			});
	});/**/
	<?php } ?>
</script>
</head>

<body>
<div id="headerbar">
	<div id="header">
		<?php include('includes/header.php'); ?>
	</div>
</div>

    <div id="website">
	<div id="content"  >
