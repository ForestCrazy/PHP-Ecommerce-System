<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['shipping_addr']) && isset($_GET['shipping_id']) && isset($_GET['payment_method']) && isset($_GET['p_id']) && isset($_GET['p_qty'])) {
        $sql_product = 'SELECT * FROM product WHERE product_id = "' . $_GET['p_id'] . '"';
        $res_product = mysqli_query($connect, $sql_product);
        if ($res_product) {
            $fetch_product = mysqli_fetch_assoc($res_product);
            $sql_shipping_price = 'SELECT shipping_price FROM product_shipping WHERE product_id = "' . $_GET['p_id'] . '" AND shipping_provider_id = "' . $_GET['shipping_id'] . '"';
            $res_shipping_price = mysqli_query($connect, $sql_shipping_price);
            if ($res_shipping_price) {
                $fetch_shipping_price = mysqli_fetch_assoc($res_shipping_price);
                $order = createOrder($_GET['shipping_addr'], $_GET['shipping_id'], $_GET['payment_method'], $fetch_shipping_price['shipping_price']);
                $order_obj = json_decode($order);
                if ($order_obj->success) {
                    $sql_update_order = 'UPDATE `order` SET product_price = "' . $fetch_product['product_price'] . '" WHERE order_id = "' . $order_obj -> orderId . '"';
                    $res_update_order = mysqli_query($connect, $sql_update_order);
                    echo createSubOrder($order_obj->orderId, $_GET['p_id'], $_GET['p_qty']);
                } else {
                    echo $order;
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 500));
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
