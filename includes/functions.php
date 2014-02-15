<?php
function sendmail($to,$sub,$msg,$replyto="no-reply@engineerstechnologies.com")
{
	/*$headers = 'From: no-reply@engineerstechnologies.com' . "\r\n" .
	'Reply-To: '.$replyto. "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to,$sub,$msg,$header);
        return;/**/
	/*$path = '/home/engin7ta/php/';
        set_include_path(get_include_path() . PATH_SEPARATOR . $path);
        require_once 'Mail.php';
	$headers = array ('From' => "no-reply@engineerstechnologies.com",
			'To' => $to,
			'Subject' => $sub);
	$smtp = Mail::factory('smtp',
		array ('host' => "smtp.mailhostbox.com",
		'port' => 25,
		'auth' => true,
		'username' => "no-reply@engineerstechnologies.com",
		'password' => 'wkni$Wl3'));
	$mail = $smtp->send($to, $headers, $msg);
	if (PEAR::isError($mail)){
		$f=fopen("mail.log","a");
		fwrite($f,"Not Sent: ".date("l dS \of F Y h:i:s A")."\n");
		fwrite($f,"To: ".$to."\n");
		fwrite($f,"Sub: ".$sub."\n");
		fwrite($f,"Msg:-------\n".$msg."\n---------\n");

                fwrite($f,PEAR::isError($mail).getMessage()."\n---------\n");

		fclose($f);
		
	}else{
                $f=fopen("mail.log","a");
		fwrite($f,"Sent on: ".date("l dS \of F Y h:i:s A")."\n");
		fwrite($f,"To: ".$to."\n");
		fwrite($f,"Sub: ".$sub."\n");
		fwrite($f,"Msg:-------\n".$msg."\n---------\n");
		fclose($f);
        }
	return;/**/
	# Include PHP Mailer Class
	require_once("class.phpmailer.php");

	# Create object of PHPMailer
	$mail = new PHPMailer();

	// Inform class to use SMTP
	$mail->IsSMTP();

	// Enable this for Testing
	$mail->SMTPDebug  = 1;

	// Enable SMTP Authentication
	$mail->SMTPAuth   = false;

	// Host of the SMTP Server
	$mail->Host = "localhost";//"smtp.mailhostbox.com";//"smtp.engineerstechnologies.com";//"ssl://outbound-us1.mailhostbox.com";

	// Port of the SMTP Server
	$mail->Port = 25;

	// SMTP User Name
	$mail->Username   = "no-reply@engineerstechnologies.com";

	// SMTP User Password
	$mail->Password = "wkni$Wl3";

	// Set From Email Address
	$mail->SetFrom("no-reply@engineerstechnologies.com", "No-reply");

	// Add Subject
	$mail->Subject    = $sub;

	// Add the body for mail
	@$mail->MsgHTML($msg);

	// Add To Address
	$mail->AddAddress($to, "");
	


	// Finally Send the Mail
	if(!$mail->Send())
	{
		$f=fopen("mail.log","a");
		fwrite($f,"\n---------\n");
		fwrite($f,"Not Sent: ".date("l dS \of F Y h:i:s A")."\n");
		fwrite($f,"To: ".$to."\n");
		fwrite($f,"Sub: ".$sub."\n");
		fwrite($f,"Msg:-------\n".$msg."\n---------\n");
                fwrite($f,$mail->ErrorInfo."\n---------\n\n");
		fclose($f);
		
	}else{
                $f=fopen("mail.log","a");
		fwrite($f,"\n---------\n");
		fwrite($f,"Sent on: ".date("l dS \of F Y h:i:s A")."\n");
		fwrite($f,"To: ".$to."\n");
		fwrite($f,"Sub: ".$sub."\n");
		fwrite($f,"Msg:-------\n".$msg."\n---------\n\n");
		fclose($f);
        }/**/

}
class MemberLogin
{
	private $verified=false;
	private $userName=NULL;
	private $userId=-1;
	private $loggedin=false;
	private $completed = false;
	function __construct()
	{
		if(isset($_SESSION['userId'])){
			$this->userId=$_SESSION['userId'];
			$this->loggedin=true;
		}
		if(isset($_SESSION['userName']))
			$this->userName=$_SESSION['userName'];
		if(isset($_SESSION['verified']) && $_SESSION['verified']=="YES")
			$this->verified=true;
		if(isset($_SESSION['completed']) && $_SESSION['completed']=="YES")
			$this->completed=true;
		
	}
	
	function isLoggedIn()
	{
		return $this->loggedin;
	}
	
	function login($id,$name,$auth="NO",$completed="NO")
	{
		$_SESSION['userId']=$id;
		$this->userId=$id;
		$this->loggedin=true;
		
		$_SESSION['userName']=$name;
		$this->userName=$name;
		
		if($auth=="YES")
		{
			$_SESSION['verified']=true;
			$this->verified=true;
		}
		if($completed=="YES"){
			$_SESSION['completed'] = true;
			$this->completed = true;
		}
	}
	
	function logout()
	{
		unset($_SESSION['userId']);
		unset($_SESSION['userName']);
		unset($_SESSION['verified']);
		unset($_SESSION['completed']);
		$this->verified=false;
		$this->completed=false;
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
	function isCompleted()
	{
		return $this->completed;
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
