<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_POST['order_id']) && isset($_FILES['paymentSlip'])) {
        $sql_order = 'SELECT status, payment_id FROM `order` WHERE order_id = "' . $_POST['order_id'] . '"';
        $res_order = mysqli_query($connect, $sql_order);
        if ($res_order) {
            $fetch_order = mysqli_fetch_assoc($res_order);
            if ($fetch_order['status'] == 'pending') {
                if (empty($fetch_order['payment_id'])) {
                    $timestamp = time();
                    if (move_uploaded_file($_FILES['paymentSlip']['tmp_name'], '../asset/img/payment_slip/' . $timestamp . '-' . $_FILES['paymentSlip']['name'])) {
                        $file_name = '/asset/img/payment_slip/' . $timestamp . '-' . $_FILES['paymentSlip']['name'];
                        $sql_payment = 'INSERT INTO payment (u_id, pay_slip) VALUES ("' . $_SESSION['u_id'] . '", "' . $file_name . '")';
                        $res_payment = mysqli_query($connect, $sql_payment);
                        if ($res_payment) {
                            $sql_update_order = 'UPDATE `order` SET payment_id = "' . mysqli_insert_id($connect) . '" WHERE order_id = "' . $_POST['order_id'] . '"';
                            $res_update_order = mysqli_query($connect, $sql_update_order);
                            if ($res_update_order) {
                                echo json_encode(array('success' => true, 'code' => 200));
                            } else {
                                echo json_encode(array('success' => false, 'code' => 500));
                            }
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    }
                }
            }
        }
    }
}
