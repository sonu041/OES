<?php
$rootpath="../";
$prgindx=12348;
include('includes/config.php'); 
if(!$loginfo->isLoggedIn()){
	//die("dadas");
	redirect("index.php");
}


$programs = Array();
$reports=Array();

$query = "SELECT prog_id, prog_name, prog_test_isrequired FROM es_prog";
$result = mysql_query($query);
while($row = mysql_fetch_row($result)){
	$programs[$row[0]] = array($row[0], $row[1], $row[2]);
}

if(isset($_GET['prid']) && $_GET['prgid'] > $prgindx) {
	$query = "SELECT * from es_std_prog where sp_prog_id = ". ($_GET['prgid'] - $prgindx);
}
?>
<?php include('includes/upper.php'); ?>
<!-- js or css here -->
<style type="text/css">
.prgpad{ padding:2px;}
img.prgopt{height: 20px; width: 20px;}
.brdrtable {border:1px solid #eee; border-collapse:collapse; padding-bottom: 5px}
textarea.text { margin-bottom:12px; width:95%; padding: .4em; }
label.error {  color: red; padding-right: 50px; padding-bottom: 50px; vertical-align: top; }
p { clear: both; }
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
#demo-frame > div.demo { padding: 10px !important; };

</style>
<script type="text/javascript" src="js/dialog.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<?php include('includes/middle.php'); ?>
<div class="prgpad">
	<div class="prgpad ui-widget-header  ui-corner-all">Program Manager</div>
</div>
<?php include('includes/lower.php'); ?>
