<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isset($_POST['withdraw_amount']) && isset($_POST['account_number']) && isset($_POST['bank_id'])) {
            if (!empty($_POST['account_number']) && !empty($_POST['bank_id'])) {
                $sql_bank_withdraw = 'SELECT bank_id FROM bank_withdraw WHERE bank_id = "' . $_POST['bank_id'] . '"';
                $res_bank_withdraw = mysqli_query($connect, $sql_bank_withdraw);
                if ($res_bank_withdraw) {
                    if (mysqli_num_rows($res_bank_withdraw) == 1) {
                        $sql_store_balance = 'SELECT store_balance FROM store WHERE store_id = "' . hasOwnStore($_SESSION['u_id']) . '"';
                        $res_store_balance = mysqli_query($connect, $sql_store_balance);
                        if ($res_store_balance) {
                            $fetch_store_balance = mysqli_fetch_assoc($res_store_balance);
                            if ($fetch_store_balance['store_balance'] >= $_POST['withdraw_amount']) {
                                $sql_update_store = 'UPDATE store SET store_balance = store_balance - ' . $_POST['withdraw_amount'] . ' WHERE store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '"';
                                $res_update_store = mysqli_query($connect, $sql_update_store);
                                if ($res_update_store) {
                                    $fees = intval($_POST['withdraw_amount'] / 100 * 3);
                                    $withdraw_balance = $_POST['withdraw_amount'] - $fees;
                                    $sql_withdraw_request = 'INSERT INTO withdraw_request (store_id, withdraw_balance, fees, bank_id, account_number) VALUES ("' . hasOwnStore($_SESSION['u_id'], true) . '", "' . $withdraw_balance . '", "' . $fees . '", "' . $_POST['bank_id'] . '", "' . $_POST['account_number'] . '")';
                                    $res_withdraw_request = mysqli_query($connect, $sql_withdraw_request);
                                    if ($res_withdraw_request) {
                                        echo json_encode(array('success' => true, 'code' => 200));
                                    } else {
                                        $sql_update_store = 'UPDATE store SET store_balance = store_balance + ' . $_POST['withdraw_amount'] . ' WHERE store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '"';
                                        $res_update_store = mysqli_query($connect, $sql_update_store);
                                        echo json_encode(array('success' => false, 'code' => 500));
                                    }
                                } else {
                                    echo json_encode(array('success' => false, 'code' => 500));
                                }
                            } else {
                                echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'ยอดเงินคงเหลือของร้านไม่เพียงพอสำหรับจำนวนที่จะถอน'));
                            }
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'code' => 10102));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10003));
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
