<?php
session_start();
require __DIR__ . '/admin/lib/db.inc.php';

$cats_li = get_navcat();
$all_prod = ierg4210_prod_fetchAll();
$products_cards = "";
foreach ($all_prod as $value) {
    $products_cards .= '<div class="col-md-4 col-sm-2"><div class="card ">
    <a href="product.php?pid=' . $value["PID"] . '"> <img class="card-img-top" src="admin/lib/images/' . $value["PID"] . '.jpg" alt="Card image cap">
    </a>
      <div class="card-body">
      <h5 class="card-title"><a href="product.php?pid=' . $value["PID"] . '"> ' . $value["NAME"] . '</a></h5>
      <p class="card-text">' . $value["DESCRIPTION"] . '</p>

      <p id="'.$value["PID"].'_price" value="'.$value["PRICE"].'">$' . $value["PRICE"] . '</p>
      </div>
      <div class="card-footer">
        <button type="button" class="btn btn-outline-success add-cart" value="'. $value["PID"] . '">Add</button>
      </div>
    </div></div>';                   
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Shopping</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="jquery-3.6.0.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=AQbAULyORFqj5mYd4U24MALpSIMVJ6EkP4xOd1ASy6UsBRC8Io7cgE0435os8l5f1-NzvsXnd47Hanyb&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>
    
</head>

<body>
    <h1>This is Liam IERG4210 shopping website</h1>
    <nav class="top-bar">
        <div class="container clearfix">
            <div class="login">
            <?php 
                if((json_decode($_COOKIE['visitor'])->role)==='1'||(json_decode($_COOKIE['visitor'])->role)==='0'){
                echo '<span style="line-height: 40px;margin-right: 1%;">Welcome back '.filter(json_decode($_COOKIE['visitor'], true)["em"]).'</span>';      
                }
                else echo '<a href="login.php">Login</a>';
            ?>
                <div class="shopping-cart">
                    <ul> 
                        
                        <?php 
                        if((json_decode($_COOKIE['visitor'])->role)==='1'){
                            echo '<li class="function"><a href="admin/admin.php" style="width:110px; text-align: center;">Admin pannel</a></li>';
                        }
                        if((json_decode($_COOKIE['visitor'])->role)==='1'||(json_decode($_COOKIE['visitor'])->role)==='0'){
                            echo '<li id="Edit" class="function"><a href="edit.php" style="width:60px; text-align: center;">Edit</a></li>';
                            echo '<li id="logout" class="function"><a href="#" style="width:60px; text-align: center;">Logout</a></li>';
                        }
                        ?>
                    </ul >
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <nav aria-label="breadcrumb ">
            <ol class="breadcrumb my-2">
                <li class="breadcrumb-item active"></li>Home</a></li>
            </ol>
        </nav>
                <div class="row">
                    <div class="col-sm-12 col-md-10 col-md-offset-1">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Total</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody id="cart_list">
                                

                            <form action='payments.php' method='post' id='form1'>
                            <input type='hidden' name='cmd' value='_cart' />
                            <input type='hidden' name='business' value='sb-cdts415570223@business.example.com' />
                            <input type='hidden' name='currency_code' value='HKD' />
                            <input type='hidden' id="charset" name='charset' value='utf-8' />
                            <?php 
                                echo "<input type='hidden' id='user' name='user' value='".$_SESSION['visitor']['em']."' />";
                            ?>
                            </tr>
                            <tr>
                            <td>   </td>
                            <td>   </td>
                            <td>
                            <div id="paypal-button-container"></div>
                             </form>
                             </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    <footer>
        <div class="container-fluid mt-2 px-2 bg-dark text-white h-3">
            <div class="d-flex flex-row justify-content-end">
                <span class="p-2">Contact&nbsp;:</span>
                <div class="p-2"><a class="text-white" href="tel:+85245141514"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                        </svg>&nbsp;+&nbsp;85245141514<a></div>

                <div class="p-2 mr-2"><a class="text-white" href="mailto:Nhl-ierg4210@gmail.com"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z" />
                        </svg>&nbsp;Nhl-ierg4210@gmail.com</a></div>
            </div>
        </div>
    </footer>
    <script>
    /* A function to simulate the communication with server */
    var custom;
    var invoice;
    var order_list;
    var total;
    function getFromServer() {
      return new Promise((resolve,reject) => {
            var dict = new Array();
            const reg = new RegExp('^[0-9]$');
            for (var key in localStorage) {
                if (localStorage.hasOwnProperty(key)&&reg.test(key)) {
                    dict.push({"PID":key,"QUANTITY":localStorage.getItem(key)});
                }
            }
            var setting=$( "#form1" ).serialize().split("&");
            setting.forEach((element,index)=>(setting[index]=element.split("=")[1]));
            $.ajax(
            {
                url: '/admin/lib/co_process.php',   // url
                method: "POST",
                async: true,
                data: { method: "checkout",cart: JSON.stringify(dict),setting:setting},// data to be submit
                success: function (data) {// success callback
                    resolve(JSON.parse(data))
                },error: function(data) 
                {
                    reject("Your order have some problem.Try again later.")
                }// data to be submit
            }
        );
      });
    }

    paypal.Buttons({
      /* Sets up the transaction when a payment button is clicked */
      createOrder: async (data, actions) => { /* async is required to use await in a function */
        /* Use AJAX to get required data from the server; For dev/demo purposes: */
        let order_details = await getFromServer().then(data=>{
            custom=data['custom_id'];
            invoice=data['digest'];
            order_list=data['order list'];
            total=data['total'];
        })
        return actions.order.create({
       /** purchase_units: [{
            custom_id:custom,
            invoice_id:invoice,
            amount: {
                        value: total,
                    },
            }]**/
            purchase_units: [{
            custom_id:custom,
            invoice_id:invoice,
            amount: {
              "currency_code": "USD",
              "value": total,
              "breakdown": {
                "item_total": {  /* Required when including the `items` array */
                  "currency_code": "USD",
                  "value": total
                }
              }
            },
            items: [
              {
                "name": order_list, /* Shows within upper-right dropdown during payment approval */
                "unit_amount": {
                  "currency_code": "USD",
                  "value": total
                },
                "quantity": 1
              },
            ]
          }]
        });
        
      },
      /* Finalize the transaction after payer approval */
      onApprove: (data, actions) => {
        return actions.order.capture().then(function (orderData) {
          /* Successful capture! For dev/demo purposes: */
          console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
          const transaction = orderData.purchase_units[0].payments.captures[0];
          localStorage.clear();
          if (orderData.status == "COMPLETED"){
          alert("Your invoice_id is ".concat(invoice))
          window.location.href = "main.php";
          }
          /* When ready to go live, remove the alert and show a success message within this page. For example: */
          // const element = document.getElementById('paypal-button-container');
          // element.innerHTML = '<h3>Thank you for your payment!</h3>';
          /* Or go to another URL:  */
          // actions.redirect('thank_you.html');
        });
      },
    }).render('#paypal-button-container');
  </script>
    <script src="logout.js"></script>
    <script src="checkout.js"></script> 
</body>

</html>