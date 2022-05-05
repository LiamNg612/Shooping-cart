<?php
session_start();
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}
if($_POST['action']=="logout"){
    session_destroy();
    if (isset($_COOKIE['visitor'])) {
        setcookie('visitor',NULL,time() - 3600,'/','s38.ierg4210.ie.cuhk.edu.hk');
        echo "You have logout";
    }
}
?>