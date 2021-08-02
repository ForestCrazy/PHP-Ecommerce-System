<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_checkFavoriteItem = 'SELECT u_id FROM product_favorite WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
    $res_checkFavoriteItem = mysqli_query($connect, $sql_checkFavoriteItem);
    if ($res_checkFavoriteItem) {
        if (mysqli_num_rows($res_checkFavoriteItem) == 0) {
        $sql_addFavoriteItem = 'INSERT INTO product_favorite (u_id, product_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $_GET['p_id'] . '")';
        $res_addFavoriteItem = mysqli_query($connect, $sql_addFavoriteItem);
        if ($res_addFavoriteItem) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'code' => '500'));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => '10001', 'reason' => 'ไม่สามารถเพิ่มสินค้าที่ชื่นชอบซ้ำได้'));
    }
    } else {
        echo json_encode(array('success' => false, 'code' => '500'));
    }
} else {
    echo json_encode(array('success' => false, 'code' => '401'));
}