<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isset($_GET['order_id']) && isset($_GET['track_code'])) {
            $sql_order = 'SELECT status FROM `order` WHERE order_id = "' . $_GET['order_id'] . '"';
            $res_order = mysqli_query($connect, $sql_order);
            if ($res_order) {
                if (mysqli_num_rows($res_order) == 1) {
                    $fetch_order = mysqli_fetch_assoc($res_order);
                    if ($fetch_order['status'] == 'processing') {
                        $sql_update_track_code = 'UPDATE `order` SET track_code = "' . $_GET['track_code'] . '" WHERE order_id = "' . $_GET['order_id'] . '"';
                        $res_update_track_code = mysqli_query($connect, $sql_update_track_code);
                        if ($res_update_track_code) {
                            echo json_encode(array('success' => true, 'code' => 200));
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'code' => 10100));
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
        echo json_encode(array('success' => false, 'code' => 403));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
