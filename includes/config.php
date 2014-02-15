<?php
/******************************************************************************
 *       @author:    Encoder                                                  *
 *       @website:   http://teamencoder.com                                *
 *       Please change the folowing variable as per your server settings..    *
 ******************************************************************************/

											   
$dbHostName='localhost';                     // database host name

$dbUserName='root';                          // database user name

$dbPassWord='';	                     // database password

$dbDatabaseName='engin7ta_exam_sys';                  // database schema name


/*
You need not edit any thing below this
*/

session_start();
date_default_timezone_set('Asia/Calcutta');
$conn=mysql_connect($dbHostName,$dbUserName,$dbPassWord);
if (!$conn)
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbDatabaseName,$conn);

function endpage()
{
	mysql_close();
	exit();
}

function redirect($str)
{
	header("location: ".$str);
	endpage();
}
include_once("functions.php");
?>
