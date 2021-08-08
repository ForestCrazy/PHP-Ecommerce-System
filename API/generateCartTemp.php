<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $cart_id = generateRandomString(20);
    foreach ($_POST['p_id'] as $key => $val) {
        $sql_cartTemp = 'INSERT INTO cart_temp (cart_id, u_id, product_id) VALUES ("' . $cart_id . '", "' . $_SESSION['u_id'] . '", "' . $val . '")';
        $res_cartTemp = mysqli_query($connect, $sql_cartTemp);
    }
    echo json_encode(array('success' => true, 'cart_token' => $cart_id));
} else {
    echo json_encode(array('success' => false, 'code' => '401'));
}