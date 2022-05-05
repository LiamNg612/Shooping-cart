<?php
    session_start();
	use PHPMailer\PHPMailer\PHPMailer;
	require "PHPMailer/PHPMailer.php";
	require "PHPMailer/Exception.php";
   
	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header('Location: main.php');
		exit();
	}
    function ipn_DB()
    {
    $ipn = new PDO('sqlite:/var/www/ipn.db');
    // FETCH_ASSOC:
    // Specifies that the fetch method shall return each row as an
    // array indexed by column name as returned in the corresponding
    // result set. If the result set contains multiple columns with
    // the same name, PDO::FETCH_ASSOC returns only a single value
    // per column name.
    $ipn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $ipn;
    }
    function order_DB()
{
    $order = new PDO('sqlite:/var/www/order.db');
    // FETCH_ASSOC:
    // Specifies that the fetch method shall return each row as an
    // array indexed by column name as returned in the corresponding
    // result set. If the result set contains multiple columns with
    // the same name, PDO::FETCH_ASSOC returns only a single value
    // per column name.
    $order->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $order;
}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
	$response = curl_exec($ch);
	curl_close($ch);
    
    $handle = fopen("resource.txt", "w");
        
	if ($response == "VERIFIED" ) {
        $txn_id = $_POST['txn_id'];
		$txn_type = $_POST['txn_type'] ;
        $item_name = $_POST['item_name'] ;
        $status = $_POST['payment_status'] ;
        fwrite($handle,$item_name);
        foreach ($_POST as $key => $value)
        fwrite($handle,$key.'='.$value.'<br />');
        $digest=hash_hmac('sha1',"HKD,sb-cdts415570223%40business.example.com,1703412284,2:3,343.0","1703412284");
        global $ipn;
        global $order;
        global $result;
        $ipn = ipn_DB();
        $order = order_DB();
        $sql = "SELECT * FROM IPNS WHERE txn_id=(?);";//add a ; at the sql statement
        $q = $ipn->prepare($sql);
        $q->bindParam(1, $txn_id);
        if($q->execute()){
            $result=$q->fetchAll();
        }
        if(!(count($result)==0&&$txn_type=="cart")){
            
            exit();
        }
        $sql = "SELECT * FROM ORDERS WHERE DETAIL=(?);";//add a ; at the sql statement
        $q = $order->prepare($sql);
        $q->bindParam(1, $item_name);
        if($q->execute()){
            fwrite($handle,"ok1");
            $result=$q->fetch();
        }
        $old_dig=$result["DIGEST"];
        $salt=$result["SALT"];
        $new_dig=hash_hmac('sha1',$item_name,$salt);
        if($new_dig==$old_dig){
            fwrite($handle,"ok3");
            $ipn = ipn_DB();
            $sql = "INSERT INTO IPNS VALUES (?,?,?);";//add a ; at the sql statement
            $qb = $ipn->prepare($sql);
            $qb->bindParam(1, $txn_id);
            $qb->bindParam(2, $txn_type);
            $qb->bindParam(3, $status);
            if($qb->execute()){

            };
        
        }
	}

    
?>
