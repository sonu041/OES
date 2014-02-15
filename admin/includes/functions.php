<?php

function sendjsondata(){
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode( $GLOBALS["data"]);
	endpage();
}
function send_error($str="")
{
	$data["error"]=$str;
	sendjsondata();
	/*header("Content-Type: application/xml; charset=utf-8");
	echo '<?xml version="1.0" encoding="utf-8"?>';
	?>
<config>
<error><?php echo htmlspecialchars($str); ?></error>
</config>
	<?php
	endpage();*/
}

class MemberLogin
{
	private $verified=false;
	private $userName=NULL;
	private $userId=-1;
	private $loggedin=false;
	
	function __construct()
	{
		if(isset($_SESSION['adminuserId'])){
			$this->userId=$_SESSION['adminuserId'];
			//$sql="select admin_uname from "
			$this->loggedin=true;
		}
		if(isset($_SESSION['adminuserName']))
			$this->userName=$_SESSION['adminuserName'];
		if(isset($_SESSION['verified']) && $_SESSION['verified']=="YES")
			$this->verified=true;
		
	}
	
	function isLoggedIn()
	{
		return $this->loggedin;
	}
	
	function login($id,$name,$auth="NO")
	{
		$_SESSION['adminuserId']=$id;
		$this->userId=$id;
		$this->loggedin=true;
		
		$_SESSION['adminuserName']=$name;
		$this->userName=$name;
		
		if($auth=="YES")
		{
			$_SESSION['verified']=true;
			$this->verified=true;
		}
	}
	
	function logout()
	{
		unset($_SESSION['adminuserId']);
		unset($_SESSION['adminuserName']);
		unset($_SESSION['verified']);
		$this->verified=false;
		$this->userName=NULL;
		$this->userId=-1;
		$this->loggedin=false;
	}
	
	function getUserId()
	{
		return $this->userId;
	}
	
	function getUserName()
	{
		return $this->userName;
	}
	
	function isVerified()
	{
		return $this->verified;
	}
	function test()
	{
	echo "test";
	}
}

function xss_decode($s){
        $s = str_replace("&","&amp;",$s);
        $s = str_replace("<","&lt;",$s);
        $s = str_replace(">","&gt;",$s);
        return $s;
}
function xss_encode($s){
        $s = str_replace("&lt;","<",$s);
        $s = str_replace("&gt;",">",$s);
        $s = str_replace("&amp;","&",$s);
        return $s;
}
function addslashes_gpc(&$k, &$v)
{
	$v = addslashes($v);
	$k = addslashes($k);
}
function stripslashes_gpc(&$k, &$v)
{
	$v = stripslashes($v);
	$k = stripslashes($k);
}
function addremovegpc($add = True){
	$fun = $add ? "addslashes_gpc" : "stripslashes_gpc";
	array_walk_recursive($_GET, $fun);
	array_walk_recursive($_POST, $fun);
	array_walk_recursive($_COOKIE, $fun);
	array_walk_recursive($_REQUEST, $fun);
}
if(get_magic_quotes_gpc())
	addremovegpc(false);
addremovegpc(true);
$loginfo=new MemberLogin();


?>
