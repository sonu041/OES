<?php
$prgindx=12348;   //What is this?
include_once("includes/config.php");
$stdid=$loginfo->getUserId();
if(!$loginfo->isLoggedIn())
{
	redirect("index.php");
}
$error="";
if(isset($_SESSION['prgerror']))
{
	$error=$_SESSION['prgerror'];
	unset($_SESSION['prgerror']);
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<style>
.profile-section-title{
	width:150px;
	float:left;
}
.profile-section-content{
	margin-left:150px;
}
.normal-bold{
	font-size:1.4em;
	font-weight:bold;
}
.profile-section-explanation>p{
	font-size:1.4em;
}
.table-form{
	width:670px;
}
.table-form td{
	padding:15px 30px 15px 0px;
	/*font-size:1.4em;*/
	vertical-align:top;
}
.table-form td p, .table-form td button{
	font-size:1em;
}

td.table-form-title{
	font-weight:bold;
	width:180px;
}

.form-not-set{
	color:#959595;
	font-style:italic;
}

div.udacity-date-picker select{
	width:100px;
}

.table-form td .button {
    font-size:0.85em;
}
.report-table {
	width:670px;
	margin-left:150px;
}
</style>
<script>
	$(function() {
		$( "#accordion" ).accordion({
			autoHeight: false,
			navigation: true
		});
		$("a.taketest").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){window.open("question.php?prgid="+$(this).attr("prgid"),"_self")})
		
		$("a.viewporg").css("cursor","pointer")
		.css("text-decoration","underline")
		.click(function(){alert("Not Implemented")})
	});

	var JTable = function() {};
	JTable.Setup = function() {
	    var table = $('.report-table');
	    $('caption', table).addClass('ui-state-default');
	    $('th', table).addClass('ui-state-default');
	    $('td', table).addClass('ui-widget-content');
	    //$(table).delegate('tr', 'hover', function() {
	    //    $('td', $(this)).toggleClass('ui-state-hover');
	    //});
	    //$(table).delegate('tr', 'click', function() {
	    //    $('td', $(this)).toggleClass('ui-state-highlight');
	    //});
	};
	$(function() {
	    JTable.Setup();
	});


</script>

<?php include('includes/middle.php'); ?>
<?php include('includes/content_profileedit.php'); ?>
<?php include('includes/lower.php'); ?>
