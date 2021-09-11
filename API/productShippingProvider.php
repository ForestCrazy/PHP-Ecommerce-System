<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['p_id'])) {
        $sql_productShipping = 'SELECT * FROM product_shipping WHERE product_id = "' . $_GET['p_id'] . '"';
        $res_productShipping = mysqli_query($connect, $sql_productShipping);
        if ($res_productShipping) {
            echo json_encode(array('success' => true, 'code' => 200, 'data' => mysqli_fetch_all($res_productShipping, MYSQLI_ASSOC)));
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
