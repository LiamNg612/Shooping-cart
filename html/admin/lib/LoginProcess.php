<?php
session_start();
include_once 'auth.php';
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
// input validation
if(isset($_POST['action'])&&($_POST['action']=="loginform"||$_POST['action']=="registerform")){
	if (empty($_POST['email']) || !preg_match('/^[\w\.]+@[\w]+(\.[\w]+)+$/', $_POST['email'])) {
		echo json_encode([ "alert" => "Your email format is wrong" ]);
	}
	else if (empty($_POST['password']) || strlen($_POST['password']) <8){
		echo json_encode([ "alert" => "Your password length should be larger than 7" ]);
	}else if($_POST['action']=="registerform"){
		register();
	}else if ($_POST['action']=="loginform"){
		login();
	}
}
if(isset($_POST['action'])&&$_POST['action']=="changepw_form"){
	
	if (empty($_POST['currentpw']) || strlen($_POST['currentpw']) <8||empty($_POST['newpw']) || strlen($_POST['newpw']) <8||empty($_POST['confirmpw']) || strlen($_POST['confirmpw']) <8){
		echo json_encode([ "alert" => "Your password length should be larger than 7" ]);
	}else if($_POST['newpw']!=$_POST['confirmpw']){
		echo json_encode([ "alert" => "Password not the same" ]);
	}else {
		changepw();
	}
}
function changepw(){
	csrfverify();
	global $db;
	$currentpw=htmlspecialchars($_POST['currentpw'], ENT_QUOTES);
	$newpw=htmlspecialchars($_POST['newpw'], ENT_QUOTES);
    $db = User_DB();
	if(($user=getuser())!=NULL){
		$salt=$user["SALT"];
		$password=hash_hmac('sha256',$currentpw, $salt);
		$newpw=hash_hmac('sha256',$newpw, $salt);
		if($password==$user["PWD"]){
			$sql = "UPDATE USER SET PWD = (?) WHERE PWD = (?);";
			$q=$db->prepare($sql);
			$q->bindParam(1,$newpw);
			$q->bindParam(2,$password);
			if ($q->execute()){
				echo (json_encode([ "message" => "Success"]));
			}else var_dump($q->errorInfo());
		}else echo json_encode([ "alert" => "Your current password does not match." ]);
	}else echo json_encode([ "alert" => "Cannot find the user." ]);
}


function User_DB()
{
    // connect to the database
    // TODO: change the following path if needed
    // Warning: NEVER put your db in a publicly accessible location
    $db = new PDO('sqlite:/var/www/user.db');
    // enable foreign key support
    $db->query('PRAGMA foreign_keys = ON;');

    // FETCH_ASSOC:
    // Specifies that the fetch method shall return each row as an
    // array indexed by column name as returned in the corresponding
    // result set. If the result set contains multiple columns with
    // the same name, PDO::FETCH_ASSOC returns only a single value
    // per column name.
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}
function register(){
	csrfverify();
	global $db;
    $db = User_DB();
	$email=htmlspecialchars($_POST['email'], ENT_QUOTES);
	$salt = mt_rand();
	$password=hash_hmac('sha256',htmlspecialchars($_POST['password'], ENT_QUOTES), $salt);
	$sql = "INSERT INTO USER (EMAIL,SALT,PWD,FLAG) VALUES (?,?,?,0);";
	$q = $db->prepare($sql);
	$q->bindParam(1, $email);
	$q->bindParam(2, $salt);
	$q->bindParam(3, $password);
	
	if ($q->execute()){
		echo json_encode([ "alert" => htmlspecialchars($_POST['password'], ENT_QUOTES)]);
	}else {
		echo json_encode([ "alert" => "Failed"]);
	}
}
function login(){
	csrfverify();
	global $db;
    $db = User_DB();
	$email=htmlspecialchars($_POST['email'], ENT_QUOTES);
	$pwd=htmlspecialchars($_POST['password'], ENT_QUOTES);
	if(($user=getuser())!=NULL){
		$salt=$user["SALT"];
		$password=hash_hmac('sha256',$pwd, $salt);
		if($password==$user["PWD"]){
			$exp=time()+3600*24*3;
			$token=array(
				'em'=>$user['EMAIL'],
				'exp'=>$exp,
				'role'=>$user['FLAG'],
				'k'=>hash_hmac('sha256',$exp.$user['PWD'],$user['SALT'])
			);
			setcookie('visitor',json_encode($token) ,$exp,'/','s38.ierg4210.ie.cuhk.edu.hk',true,true);
			$_SESSION['visitor']=$token;
			session_regenerate_id(true);
			echo json_encode([ "alert" => "Redirecting","url" => "/main.php" ]);
		}else {
			echo json_encode([ "alert" => "Your password is wrong"]);
		}
	}else {
		echo json_encode([ "alert" => "Your email address is wrong"]);
	}
	
}
function getuser(){
	global $db;
    $db = User_DB();
	$email=htmlspecialchars($_POST['email'], ENT_QUOTES);
	$sql = "SELECT * FROM USER WHERE EMAIL=(?);";
	$q=$db->prepare($sql);
	$q->bindParam(1,$email);
	if ($q->execute()){
		
		if(($user=$q->fetchAll()[0])!=NULL){
			return $user;
			}else{
			return NULL;
		}
	}else {
		var_dump($q->errorInfo());
	}
}
function csrfverify(){
	try{
		csrf_verifyNonce($_REQUEST['action'],$_POST['nonce']);
	}catch (Exception $e){
		echo json_encode([ "alert" => $e->getMessage()]);
		exit();
	}
}
?> 
 