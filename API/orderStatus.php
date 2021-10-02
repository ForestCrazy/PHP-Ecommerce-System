<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['order_id'])) {
        $sql_order = 'SELECT * FROM `order` INNER JOIN shipping_provider ON order.shipping_provider_id = shipping_provider.shipping_provider_id WHERE order.order_id = "' . $_GET['order_id'] . '"';
        $res_order = mysqli_query($connect, $sql_order);
        if ($res_order) {
            if (mysqli_num_rows($res_order) == 1) {
                $fetch_order = mysqli_fetch_assoc($res_order);
                if ($fetch_order['u_id'] == $_SESSION['u_id']) {
                    echo json_encode(array('success' => true, 'code' => 200, 'shipping_provider' => $fetch_order['shipping_name'], 'track_code' => $fetch_order['track_code']));
                } else {
                    echo json_encode(array('success' => false, 'code' => 403));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10102));
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