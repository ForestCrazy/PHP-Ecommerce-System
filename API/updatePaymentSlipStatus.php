<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_POST['payment_id']) && isset($_POST['status'])) {
        $sql_payment = 'SELECT status FROM payment WHERE payment_id = "' . $_POST['payment_id'] . '"';
        $res_payment = mysqli_query($connect, $sql_payment);
        if ($res_payment) {
            if (mysqli_num_rows($res_payment) == 1) {
                $fetch_payment = mysqli_fetch_assoc($res_payment);
                if ($fetch_payment['status'] == 'pending') {
                    $sql_update_payment = 'UPDATE payment SET status = "' . $_POST['status'] . '", update_status_time = NOW() WHERE payment_id = "' . $_POST['payment_id'] . '"';
                    $res_update_payment = mysqli_query($connect, $sql_update_payment);
                    if ($res_update_payment) {
                        echo json_encode(array('success' => true, 'code' => 200));
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'การชำระเงินไม่ได้อยู่ในสถานะรออนุมัติ'));
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