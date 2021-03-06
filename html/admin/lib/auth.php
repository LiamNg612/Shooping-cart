<?php
session_start();
include_once 'LoginProcess.php';
function auth(){
    if(!empty($_SESSION['visitor']))
        return ($_SESSION['visitor']['em']);
    if(!empty($_COOKIE['visitor'])){
        if($t=json_decode(stripslashes($_COOKIE['visitor']),true)){
            if(time()>$t['exp']){
                return false;
            }
            $db=User_DB();
            $q=$db->prepare("SELECT * FROM USER WHERE EMAIL = ?;");
			$q->execute(array($t['em']));
            return $q->fetch();
			if($r=$q->fetch()){
				$realk=hash_hmax('sha1',$t["exp"].$r["PWD"],$r["SALT"]);
				if($realk==$t['k']){
					$_SESSION['visitor']=$t;
					return $t['em'];
				}
			}
        }
    }
	return false;
}
function authadmin(){
    if($_SESSION['visitor']["role"]==="1"){
        return true;
    }
    if(json_decode($_COOKIE['visitor'], true)["role"]==="1"){
        return true;
    }
    return false;
}
function csrf_getNonce($action){
    $nonce=mt_rand().mt_rand();
    if(!isset($_SESSION['csrf_nonce']))
        $_SESSION['csrf_nonce']=array();
    $_SESSION['csrf_nonce'][$action]=$nonce;
    return $nonce;
}
function csrf_verifyNonce($action,$receivedNonce){
    if(isset($receivedNonce)&& $_SESSION['csrf_nonce'][$action]==$receivedNonce){
        if($_SESSION['visitor']==null){//???
            unset($_SESSION['csrf_nonce'][$action]);
        }
        return true;
    }
    throw new Exception('csrf-attack');
}
function filter($name){
    preg_match('/&gt;\s*[\w\-]+/',$name, $m );
    if($m!=NULL){
        return str_replace("&gt;","",$m[0]);
    }
    else return $name;
}
?>