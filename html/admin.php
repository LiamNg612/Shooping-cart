<?php
//temp block
require __DIR__ . '/lib/db.inc.php';
$res = ierg4210_cat_fetchall();
$options = '';
$products = ierg4210_prod_fetchAll();
$prods_list = '';
foreach ($res as $value) {
    $options .= '<option value="' . $value["CATID"] . '"> ' . $value["NAME"] . ' </option>';
}
foreach ($products as $value) {
    $prods_list  .= '<option value="' . $value["PID"] . '"> ' . $value["NAME"] . ' </option>';
}
?>

<html>
<header>
<link rel="stylesheet" type="text/css" href="../style.css">
</header>
<fieldset>
    <legend> New Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">
        <label for="prod_catid"> Category *</label>
        <div> <select id="prod_catid" name="catid"><?php echo ("$options"); ?></select></div>
        <label for="prod_name"> Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$" /></div>
        <label for="prod_price"> Price *</label>
        <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$" /></div>
        <label for="prod_inven"> Inventory *</label>
        <div> <input id="prod_inven" type="text" name="inventory" required="required" pattern="^\d+$" /></div>
        <label for="prod_desc"> Description *</label>
        <div> <input id="prod_desc" type="text" name="description" required="required" /> </div>
        <label for="prod_image"> Image * </label>
        <div class="drop-zone">
            <span class="drop-zone__prompt">Drop Photo</span>
            <input type="file" name="file"required="true" accept="image/jpeg" class="drop-zone__input">
        </div>
        <input type="submit" value="Submit" />
        <!--<div> <input type="file" name="file" required="true" accept="image/jpeg" /> </div>
        -->
        
    </form>
</fieldset>
<fieldset>
    <legend>Category Add Form </legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert" enctype="multipart/form-data" onsubmit="alert('success');">
        <label for="filled_cat">Category</label>
        <div> <input id="filled_cat" type="text" name="add_cat" required="required" pattern="^[A-Za-z]*+$" /></div>
        <input type="submit" value="Submit">
    </form>
</fieldset>
<fieldset id="category-delete-form">
    <legend>Category Delete Form</legend>
    <form id="cat_del" method="POST" action="admin-process.php?action=cat_delete" enctype="multipart/form-data" onsubmit="alert('success');">
        <label for="selected_cat">Category to be Delete</label>
        <select name="selected_cat" id="del-cat">
            <?php echo ("$options"); ?>
        </select>
        <input type="submit" value="Submit">
    </form>
</fieldset>
<fieldset>
    <legend>Category Update Form </legend>
    <form id="cat_update" method="POST" action="admin-process.php?action=cat_edit" enctype="multipart/form-data">
        <label for="selected_cat">Category</label>
        <div> <select id="selected_catid" name="old-cat"><?php echo ("$options"); ?></select></div>
        <label for="edit_name"> New Category *</label>
        <div> <input id="edit_name" type="text" name="name" required="required" pattern="^[\w\-]+$" /></div>
        <input type="submit" value="Submit">
    </form>
</fieldset>

<fieldset>
    <legend>Edit Product</legend>
    <form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit" enctype="multipart/form-data">
        <label for="prod_catid"> Product *</label>
        <div> <select id="prod_catid" name="pid"><?php echo $prods_list; ?></select></div>
        <label for="prod_name"> Name *</label>
        <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w-]+$" /></div>
        <label for="prod_price"> Price </label>
        <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+.?\d$" /></div>
        <label for="prod_inven"> Stocks </label>
        <div> <input id="prod_inven" type="text" name="inven" required="required" pattern="^\d+.?\d$" /></div>
        <label for="prod_desc"> Description *</label>
        <div> <textarea id="prod_desc" type="text" name="description"> </textarea></div>
        <input type="submit" value="Submit" />
    </form>
</fieldset>
<fieldset id="product-delete-form">
    <legend>Product Delete Form</legend>
    <form id="prod_del" method="POST" action="admin-process.php?action=prod_delete" enctype="multipart/form-data">
        <label for="selected_prod">Product to be Delete</label>
        <select name="selected_prod" id="del-prod">
            <?php echo ("$prods_list"); ?>
        </select>
        <input type="submit" value="Submit">
    </form>
</fieldset>

<script src="../main.js"></script>

</html>