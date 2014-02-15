<?php
include_once('includes/config.php');
/*if($loginfo->isLoggedIn()){
$sql="select NAME from alumni where alumni_id=".$loginfo->getUserId();
$row=mysql_fetch_array(mysql_query($sql));
else{
echo "test"
}*/
$sql = "SELECT admin_id, admin_uname FROM es_admin WHERE admin_uname= '".$_POST["username"]."' AND admin_password = '".$_POST["password"]."'";
$row=mysql_fetch_array(mysql_query($sql));
$loginfo->login($row['admin_id'],$row['admin_uname']);
//echo $_SESSION['userId'];
//echo $_SESSION['userName'];

redirect('index.php');
?>
