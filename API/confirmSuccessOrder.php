<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_POST['order_id'])) {
        $sql_order = 'SELECT status, payment_method, shipping_price + product_price AS total_price FROM `order` WHERE order_id = "' . $_POST['order_id'] . '"';
        $res_order = mysqli_query($connect, $sql_order);
        if ($res_order) {
            $fetch_order = mysqli_fetch_assoc($res_order);
            if ($fetch_order['status'] == 'processing') {
                $sql_update_order = 'UPDATE `order` SET status = "shipped", end_process_time = NOW() WHERE order_id = "' . $_POST['order_id'] . '"';
                $res_update_order = mysqli_query($connect, $sql_update_order);
                if ($res_update_order) {
                    if ($fetch_order['payment_method'] == 'transfer') {
                        $sql_update_store_balance = 'UPDATE store SET store_balance += "' . $fetch_order['total_price'] . '"';
                        $res_update_store_balance = mysqli_query($connect, $sql_update_store_balance);
                        if ($res_update_store_balance) {
                            echo json_encode(array('success' => true, 'code' => 200));
                        } else {
                            $sql_update_order = 'UPDATE `order` SET status = "processing", end_process_time = NULL WHERE order_id = "' . $_POST['order_id'] . '"';
                            $res_update_order = mysqli_query($connect, $sql_update_order);
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'code' => 10100));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'สถานะสินค้าไม่ได้อยู่ในระหว่างการจัดส่ง'));
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
