<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_lastShippingAddr = 'SELECT * FROM `order` WHERE u_id = "' . $_SESSION['u_id'] . '" ORDER BY order_id DESC LIMIT 1';
    $res_lastShippingAddr = mysqli_query($connect, $sql_lastShippingAddr);
    if ($res_lastShippingAddr) {
        if (mysqli_num_rows($res_lastShippingAddr) == 1) {
            $fetch_lastShippingAddr = mysqli_fetch_assoc($res_lastShippingAddr);
            echo json_encode(array('success' => true, 'code' => 200, 'lastShippingAddr' => $fetch_lastShippingAddr['address_id']));
        } else {
            echo json_encode(array('success' => true, 'code' => 200, 'lastShippingAddr' => 0));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 500));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
