<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['order_id']) && isset($_GET['p_id']) && isset($_GET['p_qty'])) {
        $sql_product = 'SELECT product_price FROM product WHERE product_id = "' . $_GET['p_id'] . '"';
        $res_product = mysqli_query($connect, $sql_product);
        if ($res_product) {
            if (mysqli_num_rows($res_product) == 1) {
                $fetch_product = mysqli_fetch_assoc($res_product);
                $sql_sub_order = 'INSERT INTO sub_order (order_id, product_id, quantity, total_price) VALUES ("' . $_GET['order_id'] . '", "' . $_GET['p_id'] . '", "' . $_GET['p_qty'] . '", "' . $_GET['p_qty'] * $fetch_product['product_price'] . '")';
                $res_sub_order = mysqli_query($connect, $sql_sub_order);
                if ($res_sub_order) {
                    echo json_encode(array('success' => true, 'code' => 200));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10102, 'reason' => 'ไม่พบสินค้าชิ้นนี้ในฐานข้อมูล'));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}