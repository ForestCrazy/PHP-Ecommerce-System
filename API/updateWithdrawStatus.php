<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isAdmin($_SESSION['u_id'])) {
        if (isset($_POST['withdraw_id']) && isset($_POST['status'])) {
            if (isset($_FILES['withdrawSlip']) || $_POST['status'] == 'decline') {
                $sql_withdraw = 'SELECT status, (withdraw_balance + fees) AS withdraw_amount, store_id FROM withdraw_request WHERE withdraw_id = "' . $_POST['withdraw_id'] . '"';
                $res_withdraw = mysqli_query($connect, $sql_withdraw);
                if ($res_withdraw) {
                    if (mysqli_num_rows($res_withdraw) == 1) {
                        $fetch_withdraw = mysqli_fetch_assoc($res_withdraw);
                        if ($fetch_withdraw['status'] == 'pending') {
                            if ($_POST['status'] == 'decline') {
                                $sql_update_withdraw = 'UPDATE withdraw_request SET status = "decline", update_status_time = NOW() WHERE withdraw_id = "' . $_POST['withdraw_id'] . '"';
                                $res_update_withdraw = mysqli_query($connect, $sql_update_withdraw);
                                if ($res_update_withdraw) {
                                    $sql_update_balance_store = 'UPDATE store SET store_balance = store_balance + ' . $fetch_withdraw['withdraw_amount'] . ' WHERE store_id = "' . $fetch_withdraw['store_id'] . '"';
                                    $res_update_balance_store = mysqli_query($connect, $sql_update_balance_store);
                                    if ($res_update_balance_store) {
                                        echo json_encode(array('success' => true, 'code' => 200));
                                    } else {
                                        $sql_update_withdraw = 'UPDATE withdraw_request SET status = "decline", update_status_time = NULL WHERE withdraw_id = "' . $_POST['withdraw_id'] . '"';
                                        $res_update_withdraw = mysqli_query($connect, $sql_update_withdraw);
                                        echo json_encode(array('success' => false, 'code' => 500));
                                    }
                                } else {
                                    echo json_encode(array('success' => false, 'code' => 500));
                                }
                            } else {
                                $timestamp = time();
                                if (move_uploaded_file($_FILES['withdrawSlip']['tmp_name'], '../asset/img/withdraw_slip/' . $timestamp . '-' . $_FILES['withdrawSlip']['name'])) {
                                    $filename = '/asset/img/withdraw_slip/' . $timestamp . '-' . $_FILES['withdrawSlip']['name'];
                                    $sql_update_withdraw = 'UPDATE withdraw_request SET status = "approve", update_status_time = NOW(), withdraw_slip = "' . $filename . '" WHERE withdraw_id = "' . $_POST['withdraw_id'] . '"';
                                    $res_update_withdraw = mysqli_query($connect, $sql_update_withdraw);
                                    if ($res_update_withdraw) {
                                        echo json_encode(array('success' => true, 'code' => 200));
                                    } else {
                                        echo json_encode(array('success' => false, 'code' => 500));
                                    }
                                } else {
                                    echo json_encode(array('success' => false, 'code' => 500));
                                }
                            }
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
            echo json_encode(array('success' => false, 'code' => 10001));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 403));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
