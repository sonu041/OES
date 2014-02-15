<?php include_once('includes/config.php');?>
<?php if (!isset($_SESSION['userId'])) {
  redirect('index.php');
}?>
<?php include_once('includes/upper.php'); ?>
<!-- js or css here -->
<script>
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var oldpass = $( "#changepass-old" ),
			newpass = $( "#changepass-new" ),
			reenter = $( "#changepass-reenter" ),
			allFields = $( [] ).add( oldpass ).add( newpass ).add( reenter ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkPassMatch( n, r ) {
			if (   n.val() != r.val() ) {
				n.addClass( "ui-state-error" );
				updateTips( "Password doesnot match" );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#changepass-dialog-form" ).dialog({ 
			//autoOpen: false,
			//height: 300,
			//width: 350,
			modal: true,
			buttons: {
				"Change Password": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( oldpass, "oldpass", 6, 15 );
					bValid = bValid && checkLength( newpass, "newpass", 6, 15 );
					bValid = bValid && checkLength( reenter, "reenter", 6, 15 );

					bValid = bValid && checkPassMatch( newpass, reenter );

					if ( bValid ) {
						$("#changepassword").submit()
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

		$( "#change-pass" )
			.button()
			.click(function() {
				$( "#changepass-dialog-form" ).dialog( "open" );
			});
	});
</script>
<?php include_once('includes/middle.php'); ?>
<?php include_once('includes/content_changepass.php'); ?>
<?php include_once('includes/lower.php'); ?>
