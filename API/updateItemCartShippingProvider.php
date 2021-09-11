<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['p_id']) && isset($_GET['shipping_id'])) {
        $sql_updateItemCartShippingProvider = 'UPDATE cart SET shipping_id = "' . $_GET['shipping_id'] . '" WHERE product_id = "' . $_GET['p_id'] . '" AND u_id = "' . $_SESSION['u_id'] . '"';
        $res_updateItemCartShippingProvider = mysqli_query($connect, $sql_updateItemCartShippingProvider);
        if ($res_updateItemCartShippingProvider) {
            echo json_encode(array('success' => true, 'code' => 200));
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}