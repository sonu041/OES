<style type="text/css">
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
	#m1 a {cursor: pointer; }
</style>
<script type="text/javascript">
<?php if(!$loginfo->isLoggedIn())
		{ ?>
	$(function() {
		
		/***********************
		 *      variable       *
		 ***********************/
		$("iframe")	.hide();	 
		$(".hideme").removeClass("hideme")
		 
		 var email = $( "#email" ),
			password = $( "#password" ),
			allFields = $( [] ).add( email ).add( password ),
			tips = $( ".validateTips" );
		
		//try{
		var register_name = $( "#register-name" );
			register_email = $( "#register-email" );
			register_contact = $( "#register-contact" )
			register_allFields = $( [] ).add( register_name ).add( register_email ).add( register_contact );	
			register_tips = $( ".register_validateTips" );
		//}catch(e){alert(e)}
		/**/
		var	forgetpwd_email = $( "#forgetpwd-email" ),
			forgetpwd_allFields = $( [] ).add( forgetpwd_email ),
			forgetpwd_tips = $( ".forgetpwd-validateTips" );
		/*********************
		 *  initiate dialog  *
		 *********************/
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		
	
		$( "#login-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			stack: true,
			buttons: {
				"Login": {
					text: "Login",
					id: "idlogmein",
					click: function() {
						var bValid = true;
						allFields.removeClass( "ui-state-error" );

						if ( bValid ) {
							$("#logmein").submit()
						}
				}
					},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				clearform("logmein")
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		$("#login-form").keyup(function(event){   			
			if(event.keyCode == 13){
        		$("#idlogmein").click();
    			}
		});

		$( "#login-user" )
			//.button()
			.click(function() {
				$(".redirectto").val("index.php")
				$( "#login-form" ).dialog( "open" );
			});
		
		
		try{
		$("#createuser")
		.click(function(){
			//$( "#dialog:ui-dialog" ).dialog( "destroy" );
			$( "#register-dialog-form" ).dialog( "open" );
		})
		
		$("#forgetpassword")
		.click(function(){
			$( "#forgetpwd-dialog-form" ).dialog("open")
		})
		}catch(e){alert(e)}
		
		$( "#register-dialog-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			stack: true,
			buttons: {
				"Create an account": {
					text: "Create new account",
					id: "idregister",
					click: function() {
						var bValid = true;
						register_allFields.removeClass( "ui-state-error" );

						bValid = bValid && checkLength( register_name, "name", 3, 40 );
						bValid = bValid && checkLength( register_email, "email", 6, 40 );
						bValid = bValid && checkLength( register_contact, "contact", 5, 16 );

						//bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
						bValid = bValid && checkRegexp( register_email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
						//bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );

						if ( bValid ) {
							$("#registerme").submit()
						}
					}
				},
				Cancel: function() {
					
					//$("#register-dialog-form .ui-widget-overlay").css("background-color","#00ff00");
					$( this ).dialog( "close" );
				}
			},/**/
			close: function() {
				register_allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		
		$("#register-dialog-form").keyup(function(event){   			
			if(event.keyCode == 13){
        		$("#idregister").click();
    			}
		});

		/**/
		$( "#forgetpwd-dialog-form" ).dialog({
			modal: true,
			stack:true,
			autoOpen: false,
			
			height: 400,
			width: 350,
			buttons: {
				"Submit": {
					text: "Reset Password",
					id: "idforget",
					click: function() {
						try{
						var bValid = true;
						forgetpwd_allFields.removeClass( "ui-state-error" );
						bValid = bValid && checkLength( forgetpwd_email, "email", 6, 40 );
						bValid = bValid && checkRegexp( forgetpwd_email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
						if ( bValid ) {
							$("#forgetpwd").submit()
						}
						}catch(e){alert(e)}
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				forgetpwd_allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
		/*$("#forgetpwd-dialog-form").keyup(function(event){   			
			if(event.keyCode == 13){
        		$("#idforget").click();
    			}
		});*/
	});
		
		/***********************
		 *  Auxilary function  *
		 ***********************/
		function updateTips( t ) {
			register_tips
				.text( t )
				.addClass( "ui-state-highlight" )
				.show()
			setTimeout(function() {
				register_tips.removeClass( "ui-state-highlight", 1500 )
				.fadeOut("slow")
			}, 500 );
		}
		/***********************
		 *  Auxilary function  *
		 ***********************/
		
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
	function clearform(formid){
		//alert(formid)
		$("#"+formid).each (function(){
		  this.reset();
		});
	}
	function loadagain(urlTo){
		window.open(urlTo,'_self');
	}
	function seterror(idx){
		//alert(idx)
		try{
		$("#"+idx).addClass( "ui-state-error" )
		}catch(e){alert(e)}
	}
</script>
</head>

<body><a name="top"></a>
	<div id="headerbar">
	<div id="header">
		<?php include('includes/header.php'); ?>
	</div>
	</div>
    <div id="website">
	
	<div id="content"  >
