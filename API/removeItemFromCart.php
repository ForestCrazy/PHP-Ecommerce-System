<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_removeItemFromCart = 'DELETE FROM cart WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
    $res_removeItemFromCart = mysqli_query($connect, $sql_removeItemFromCart);
    if ($res_removeItemFromCart) {
        echo json_encode(array('success' => true, 'code' => 204));
    } else {
        echo json_encode(array('success' => false, 'code' => 500));
    }
}
