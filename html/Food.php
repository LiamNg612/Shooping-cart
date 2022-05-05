<?php
//temp block
require __DIR__ . '/lib/db.inc.php';
$currentPage = $_SERVER['SCRIPT_NAME'];
$currentPage = str_replace(array('/', '.php'), '', $currentPage);

$cats_li = get_navcat();

$products_cards = html_prods("Food");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Shopping</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>
    <h1>This is Liam IERG4210 shopping website</h1>
    <nav class="top-bar">
        <div class="container clearfix">
            <div class="login">
                <a href="#">Login</a>
                <div class="shopping-cart">
                    <ul>
                        <li id="cart"><a href="#">Cart</a>
                            <ul>
                                <li><a href="food-prod/ice-cream.html">
                                        <div class="product">
                                            <span class="name">Ice cream</span>
                                            <span class="number">1</span>
                                        </div>
                                    </a></li>
                                <li><a href="pets-prod/dog.html">
                                        <div class="product">
                                            <span class="name">Dog</span>
                                            <span class="number">1</span>
                                        </div>
                                    </a></li>
                                <li><a href="clothes-prod/hat.html">
                                        <div class="product">
                                            <span class="name">Hat</span>
                                            <span class="number">1</span>
                                        </div>
                                    </a></li>
                                <li><a href="#">
                                        <div class="total">
                                            <span>Total:</span>
                                            <span class="amount">$5188</span>
                                        </div>
                                    </a></li>
                                <li class="my-2">
                                    <button type="button" class="btn btn-success mx-auto">Check out</button>
                                </li>
                            </ul>
                        </li>
                    </ul>
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
    </div>
    <!--  changed part -->
    <div class="container p-0">

        <div class="row">
            <div class="col-md-3 ">
                <ul class="category ">
                    <li class="nav-item">
                        Category
                    </li>
                    <?php echo $cats_li; ?>
                </ul>
            </div>
            <div class="col-md-9 ">
                <div class="card-group">
                    <?php echo $products_cards; ?>
                </div>
            </div>
        </div>

    </div>

    <!--  ended part -->

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
</body>

</html>