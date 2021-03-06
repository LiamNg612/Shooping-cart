<?php
include_once 'auth.php';
function ierg4210_DB()
{
    // connect to the database
    // TODO: change the following path if needed
    // Warning: NEVER put your db in a publicly accessible location
    $db = new PDO('sqlite:/var/www/cart.db');

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
function ierg4210_cat_fetchall()
{
    // DB manipulation
    global $db;
    $db = ierg4210_DB();
    $q = $db->prepare("SELECT * FROM categories LIMIT 100;");
    try {
        $q->execute();
        return $q->fetchAll();
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage();
    }
}
function ierg4210_prod_adminfetchAll()
{
    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS ; ";
    $q = $db->prepare($sql);
    if ($q->execute())
        return $q->fetchAll();
}
function ierg4210_prod_fetchAll()
{
    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS LIMIT 6; ";
    $q = $db->prepare($sql);
    if ($q->execute())
        return $q->fetchAll();
}
function ierg4210_prod_fetchOne($PID)
{
    global $db;
    $db = ierg4210_DB();

    $sql = "SELECT * FROM PRODUCTS WHERE PID=(?);";
    $q = $db->prepare($sql);
    $q->bindParam(1, $PID);
    if ($q->execute())
        return $q->fetchAll();
}

function ierg4210_prod_fetchcurrent($CATID)
{
    global $db;
    $db = ierg4210_DB();
    $CATID=htmlspecialchars($CATID, ENT_QUOTES);
    $sql = "SELECT * FROM PRODUCTS WHERE CATID=(?) LIMIT 6; ";
    $q = $db->prepare($sql);
    $q->bindParam(1, $CATID);
    if ($q->execute())
        return $q->fetchAll();
}
// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function ierg4210_prod_insert()
{
    // input validation or sanitization
    // DB manipulation
    global $db;
    $db = ierg4210_DB();

    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['catid']))
        throw new Exception("invalid-catid");
    $_POST['catid'] = (int) $_POST['catid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
        throw new Exception("invalid-text");
    if (!preg_match('/^\d+$/', $_POST['inventory']))
        throw new Exception("invalid-inventory");

    $sql = "INSERT INTO products (catid, name, price, description,inventory) VALUES (?,?, ?, ?,?);";//add a ; at the sql statement
    $q = $db->prepare($sql);

    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if (
        $_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000
    ) {

        $catid = htmlspecialchars($_POST["catid"], ENT_QUOTES);
        $name = ucwords(strtolower($_POST["name"]));
        $name=htmlspecialchars($name, ENT_QUOTES);
        $price = htmlspecialchars($_POST["price"], ENT_QUOTES);
        $desc = htmlspecialchars($_POST["description"], ENT_QUOTES);
        $inventory = htmlspecialchars($_POST["inventory"], ENT_QUOTES);
        $sql = "INSERT INTO products (catid, name, price, description,inventory) VALUES (?, ?, ?, ?,?);";
        $q = $db->prepare($sql);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $name);
        $q->bindParam(3, $price);
        $q->bindParam(4, $desc);
        $q->bindParam(5, $inventory);
        $q->execute();
        $lastId = $db->lastInsertId();
        //resize($_FILES["file"]["name"],$lastId);
        // Note: Take care of the permission of destination folder (hints: current user is apache)
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/admin/lib/images/" . $lastId . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}
function resize($file,$lastId){
    $original_image=imagecreatefromjpeg($file);
    $width=imagesx($original_image);
    $height=imagesy($original_image);
    $ratio=500/$width;
    $new_width=500;
    $new_height=$height*$ratio;
    if($new_height>500){
        $ratio=500/$height;
        $new_height=500;
        $new_width=$width*$ratio;
    }
    if($original_image){
        $new_image=imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$width,$height);
        imagejpeg($new_image, '/var/www/html/admin/lib/images/resize/'.$lastId.'.jpg', 100);
    }
    
}
// TODO: add other functions here to make the whole application complete
function ierg4210_cat_insert()
{
    global $db;
    $db = ierg4210_DB();
    $add_cat = ucwords(strtolower($_POST["add_cat"]));
    $add_cat=htmlspecialchars($add_cat, ENT_QUOTES);
    $sql = "INSERT INTO categories (NAME) VALUES (?);";
    $q = $db->prepare($sql);
    $q->bindParam(1, $add_cat);
    if ($q->execute())
        header('Location: admin.php');
    exit();
}


function ierg4210_cat_edit()
{
    global $db;
    $db = ierg4210_DB();
    $old_cat = htmlspecialchars($_POST["old-cat"], ENT_QUOTES);
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
    $sql = "UPDATE CATEGORIES SET name=? WHERE CATID=?;";
    $q = $db->prepare($sql);
    $q->bindParam(1, $name);
    $q->bindParam(2,  $old_cat);
    $res = $q->execute();
    header('Location: admin.php');
    exit();
}


function ierg4210_cat_delete()
{
    global $db;
    $db = ierg4210_DB();
    $selected_cat = htmlspecialchars($_POST["selected_cat"], ENT_QUOTES);
    $sql = "DELETE FROM CATEGORIES WHERE CATID=(?);";
    $q = $db->prepare($sql);
    $q->bindParam(1, $selected_cat);
    $res = $q->execute();
    header('Location: admin.php');
    exit();
}


function ierg4210_prod_delete_by_catid()
{
}

function ierg4210_prod_edit()
{

    global $db;
    $db = ierg4210_DB();
    $pid = htmlspecialchars($_POST['pid'], ENT_QUOTES);
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $new_price = htmlspecialchars($_POST['price'], ENT_QUOTES);
    $new_desc = htmlspecialchars($_POST['description'], ENT_QUOTES);
    $new_inven = htmlspecialchars($_POST['inven'], ENT_QUOTES);
    // TODO: complete the rest of the INSERT command
    if (!preg_match('/^\d*$/', $_POST['pid']))
        throw new Exception("invalid-pid");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price ");
    if (!preg_match('/^[\w- ]+$/', $_POST['description']))
        throw new Exception("invalid-description");
    if (!preg_match('/^[\d.]+$/', $_POST['inven']))
        throw new Exception("invalid-number");
    $sql = "UPDATE products SET name = (?), PRICE = (?), description = (?), inventory = (?) WHERE (pid) = (?);";

    $q = $db->prepare($sql);
    $q->bindParam(1, $name);
    $q->bindParam(2, $new_price);
    $q->bindParam(3, $new_desc);
    $q->bindParam(4, $new_inven);
    $q->bindParam(5, $pid);
    if ($q->execute()) {
        header('Location: admin.php');
        exit();
    }
}
function ierg4210_prod_delete()
{
    global $db;
    $db = ierg4210_DB();
    $selected_prod = htmlspecialchars($_POST["selected_prod"], ENT_QUOTES);
    $file='/var/www/html/admin/lib/images/'.$selected_prod.'.jpg';
    $sql = "DELETE FROM PRODUCTS WHERE PID=(?);";
    $q = $db->prepare($sql);
    unlink($file);
    $q->bindParam(1, $selected_prod);
    $res = $q->execute();
    header('Location: admin.php');
    exit();
}
function html_prods($CATID)
{
    $all_prod = ierg4210_prod_fetchcurrent($CATID);
    $products_cards = "";
    foreach ($all_prod as $value) {
        $products_cards .= '<div class="col-md-4 col-sm-2"><div class="card ">
                            <a href="product.php?pid=' . filter($value["PID"]) . '"> <img class="card-img-top" src="admin/lib/images/' . filter($value["PID"]) . '.jpg" alt="Card image cap">
                            </a>
                              <div class="card-body">
                              <h5 class="card-title"><a href="product.php?pid=' . filter($value["PID"]) . '"> ' . filter($value["NAME"]) . '</a></h5>
                              <p class="card-text">' . filter($value["DESCRIPTION"]) . '</p>

                              <p id="'.filter($value["PID"]).'_price" value="'.filter($value["PRICE"]).'">$' . filter($value["PRICE"]) . '</p>
                              </div>
                              <div class="card-footer">
                                <button type="button" class="btn btn-outline-success add-cart" value="'. filter($value["PID"]) . '">Add</button>
                              </div>
                            </div></div>';
    }
    return $products_cards;
}
function html_Oneprod($info)
{
    $product_info = "";
    foreach ($info as $value) {
        $product_info .= '<div class="card">
                                 <div class="row no-gutters">
                                     <div class="col-sm-5" style="background: #868e96;">
                                        <img src="/admin/lib/images/' . filter($value["PID"]) . '.jpg " class="card-img-top h-100"> 
                                    </div>
                                     <div class="col-sm-7">
                                        <div class="card-body">
                                            <h5 class="card-title">' . filter($value["NAME"]) . '</h5>
                                             <p class="card-text">' . filter($value["DESCRIPTION"]) . '<p class="card-text">$' . filter($value["PRICE"]) . '</p>
                                            <button class="btn btn-primary add-cart" value="'. filter($value["PID"]) . '">Add</button>
                                            <span class="card-text"> ' . filter($value["INVENTORY"]) . ' left</span>
                                           
                                        </div>
                                 </div>
                        </div>
                        </div>';
    }
    return $product_info;
}
function get_navcat()
{
    $res = ierg4210_cat_fetchall();
    $cat_li = "";
    foreach ($res as $value) {
        $cat_li .= '<li class="py-3"><a href="cart.php?catid=' . filter($value["CATID"]) . '"> ' . filter($value["NAME"]) . '</a> </li>';
    }
    return $cat_li;
}
function getcart_breadcrumb($catid){
    global $db;
    $db = ierg4210_DB();
    $catid=htmlspecialchars($catid, ENT_QUOTES);
    $sql = "SELECT NAME FROM CATEGORIES WHERE CATID=(?);";
    $q = $db->prepare($sql);
    $q->bindParam(1, $catid);
    if ($q->execute())
        $res=$q->fetchAll();
        return $res[0]['NAME'];
    exit();
}
if($_POST['method']=="pid"){
    $PID=htmlspecialchars($_POST['pid'], ENT_QUOTES);
    $output="";
    $prod_info=ierg4210_prod_fetchOne($PID);
    $output.='<li id="'.$prod_info[0][PID].'">
        <div class="product">
            <span class="name">'.filter($prod_info[0][NAME]).'</span>
            <button class="btn btn-primary btn-sm minus_product" id="pid'.filter($prod_info[0][PID]).'_minusbtn">-</button>
            <input min="0" type="number" pattern="[0-9]+" class="amount" id="pid'.filter($prod_info[0][PID]).'_amount" style="width:15%">
            <button class="btn btn-primary btn-sm add_product" id="pid'.filter($prod_info[0][PID]).'_addbtn">+</button>
            <button class="btn btn-primary btn-sm delete_product" id="pid'.filter($prod_info[0][PID]).'_delbtn">Delete</button>
            <span class="total" id="pid'.filter($prod_info[0][PID]).'_price">'.filter($prod_info[0][PRICE]).'</span>
            <span class="price" id="'.filter($prod_info[0][PID]).'orginal_price" style="display:none;" >'.filter($prod_info[0][PRICE]).'</span>
        </div>
    </li>';
    echo $output;
}
if(isset($_POST['page'])){
    $page=$_POST['page'];
    $num=$page*6;
    $output="";
    global $db;
    $db = ierg4210_DB();
    if(isset($_POST['catid'])){
        $sql = "SELECT * FROM PRODUCTS WHERE CATID=(?) LIMIT(?),6;";
        $q = $db->prepare($sql);
        $catid=htmlspecialchars($_POST['catid'], ENT_QUOTES);
        $q->bindParam(1, $catid);
        $q->bindParam(2, $num);
    }
    else {
        $sql = "SELECT * FROM PRODUCTS LIMIT (?),6;";
        $q = $db->prepare($sql);
        $q->bindParam(1, $num);
    }
    
    try {
        $q->execute();
        $currentpage=$q->fetchAll();
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage();
    }
    foreach ($currentpage as $value) {
        $output .= '<div class="col-md-4 col-sm-2"><div class="card ">
        <a href="product.php?pid=' . filter($value["PID"]) . '"> <img class="card-img-top" src="admin/lib/images/' . filter($value["PID"]) . '.jpg" alt="Card image cap">
        </a>
          <div class="card-body">
          <h5 class="card-title"><a href="product.php?pid=' . filter($value["PID"]) . '"> ' . filter($value["NAME"]) . '</a></h5>
          <p class="card-text">' . filter($value["DESCRIPTION"]) . '</p>
    
          <p id="'.filter($value["PID"]).'_price" value="'.filter($value["PRICE"]).'">$' . filter($value["PRICE"]) . '</p>
          </div>
          <div class="card-footer">
            <button type="button" class="btn btn-outline-success add-cart" value="'. filter($value["PID"]) . '">Add</button>
          </div>
        </div></div>';            
    }
    echo $output; 
}