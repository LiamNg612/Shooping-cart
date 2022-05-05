<?php 
session_start();
include_once 'admin/lib/auth.php';
echo "session".$_SESSION['visitor']["em"];
echo "cookie".$_COOKIE['visitor'];
if(auth()!=false){
    header('Location:/main.php');
 }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="jquery-3.6.0.js"></script>
</head>

<body>
    <div class="col-md-6 offset-md-3">
        <h3>Login</h3>
        <form id="loginform" >
            <div class="form-group" >
                <label for="loginemail">Email address</label>
                <input name="email" type="email" class="form-control" id="loginemail" placeholder="Enter email">
                <small id="login_emailverify" class="form-text"></small>
            </div>
            <div class="form-group">
                <label for="loginpw">Password</label>
                <input name="password" type="password" class="form-control" id="loginpw" placeholder="Password">
                <small id="login_pwverify" class="form-text"></small></div>
                <input type="hidden" name="nonce" value="<?php echo csrf_getNonce("loginform"); ?>"/>
               
            <button id="login" type="button" class="btn btn-primary login_btns">Login</button>
        </form> 
    </div>
    <div class="col-md-6 offset-md-3">
        <h3>Sign Up</h3>
        <form id="registerform">
            
            <div class="form-group" >
                <label for="registeremail">Email address</label>
                <input name="email" type="email" class="form-control" id="registeremail" placeholder="Enter email">
                <small id="register_emailverify" class="form-text"><?php echo $action?></small>
            </div>
            <div class="form-group">
                <label for="registerpw">Password</label>
                <input name="password" type="password" class="form-control" id="registerpw" placeholder="Password">
                <small id="register_pwverify" class="form-text"></small>
            </div>
            <input type="hidden" name="nonce" value="<?php echo csrf_getNonce("registerform"); ?>"/>
            <button id="register" type="button" class="btn btn-primary login_btns">Register</button>
        </form>
</div>
        <script src="login.js"></script>
        
</script>
</body>



</html>