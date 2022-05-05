<?php
//temp block
session_start();
require __DIR__ . '/admin/lib/db.inc.php';
include_once 'admin/lib/auth.php';

if(auth()==false){
    header('Location:login.php');
 }

?>
<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="jquery-3.6.0.js"></script>
</head>

<body>
<div class="col-md-6 offset-md-3">
        <h3>Change Password</h3>
        <form id="changepw_form" >
            <div id="currentpw"class="form-group" >
                <label for="currentpw">Current password</label>
                <input name="currentpw" type="password" class="form-control">
                <small id="current_message" class="message"></small>
            </div>
            <div id="newpw" class="form-group">
                <label  id="newpw" for="newpw">New Password</label>
                <input name="newpw" type="password" class="form-control">
                <small id="new_message" class="message"></small></div>
            <div id="confirmpw" class="form-group">
                <label for="confirmpw">Confirm New Password</label>
                <input name="confirmpw" type="password" class="form-control">
                <small id="confirm_message" class="message"></small></div>
                <?php
                    echo '<input name="email" id="em" value="'.json_decode($_COOKIE['visitor'])->em.'" style="display:none;" readonly>';
                ?>
                 <input type="hidden" name="nonce" value="<?php echo csrf_getNonce("changepw_form"); ?>"/>
            <button id="changebtn" type="button" class="btn btn-primary login_btns">Change</button>
        </form> 
        <br>
        <button><a href="main.php">Back to home page</a></button>
    </div>
    <script src="/changepw.js"></script>
</body>


</html>