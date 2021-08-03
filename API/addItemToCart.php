<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    try {
        $sql_addToCart = 'INSERT INTO cart (u_id, product_id, quantity, shipping_id) VALUES ("' . $_SESSION['u_id'] . '", "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '", "' . $_GET['quantity'] . '", "' . mysqli_real_escape_string($connect, $_GET['shipping_id']) . '")';
        $res_addToCart = mysqli_query($connect, $sql_addToCart);
        if ($res_addToCart) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'code' => '500'));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'code' => '500', 'err' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'code' => '401'));
}
