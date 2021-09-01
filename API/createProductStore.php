<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        $sql_addProduct = 'INSERT INTO product (product_name, product_description, product_quantity, store_id, product_price) VALUES ("' . mysqli_real_escape_string($connect, $_POST['productName']) . '", "' . $_POST['productDescription'] . '", "' . mysqli_real_escape_string($connect, $_POST['productQty']) . '", "' . hasOwnStore($_SESSION['u_id'], true) . '", "' . mysqli_real_escape_string($connect, $_POST['productPrice']) . '")';
        $res_addProduct = mysqli_query($connect, $sql_addProduct);
        if ($res_addProduct) {
            echo json_encode(array('success' => true, 'productId' => mysqli_insert_id($connect), 'code' => 200));
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 403));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}