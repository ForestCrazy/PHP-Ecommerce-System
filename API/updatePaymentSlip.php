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
                        $sql_payment = 'INSERT INTO payment (u_id, pay_slip, order_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $file_name . '", "' . $_POST['order_id'] . '")';
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
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500));
                    }
                } else {
                    $sql_payment = 'SELECT status FROM payment WHERE payment_id = "' . $fetch_order['payment_id'] . '"';
                    $res_payment = mysqli_query($connect, $sql_payment);
                    if ($res_payment) {
                        if (mysqli_num_rows($res_payment) == 1) {
                            $fetch_payment = mysqli_fetch_assoc($res_payment);
                            if ($fetch_payment['status'] == 'decline') {
                                $timestamp = time();
                                if (move_uploaded_file($_FILES['paymentSlip']['tmp_name'], '../asset/img/payment_slip/' . $timestamp . '-' . $_FILES['paymentSlip']['name'])) {
                                    $file_name = '/asset/img/payment_slip/' . $timestamp . '-' . $_FILES['paymentSlip']['name'];
                                    $sql_payment = 'INSERT INTO payment (u_id, pay_slip, order_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $file_name . '", "' . $_POST['order_id'] . '")';
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
                                } else {
                                    echo json_encode(array('success' => false, 'code' => 500));
                                }
                            } else {
                                echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'การชำระเงินไม่ได้อยู่ในสถานะถูกปฏิเสธหรือรอการอนุมัติ'));
                            }
                        } else {
                            echo json_encode(array('success' => false, 'code' => 10102));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500));
                    }
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'สถานะออเดอร์ไม่ได้อยู่ในระหว่างรอการชำระเงิน'));
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
