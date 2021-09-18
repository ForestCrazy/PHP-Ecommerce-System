<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_GET['p_id'])) {
    $sql_product = 'SELECT product_quantity FROM product WHERE product_id = "' . $_GET['p_id'] . '"';
    $res_product = mysqli_query($connect, $sql_product);
    if ($res_product) {
        if (mysqli_num_rows($res_product) == 1) {
            $fetch_product = mysqli_fetch_assoc($res_product);
            echo json_encode(array('success' => true, 'code' => 200, 'productQty' => $fetch_product['product_quantity']));
        } else {
            echo json_encode(array('success' => false, 'code' => 10102));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 500));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 10001));
}
