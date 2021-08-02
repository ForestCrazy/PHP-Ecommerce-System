<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_addFavoriteItem = 'INSERT INTO product_favorite (u_id, product_id) VALUES ("' . $_SESSION['u_id'] . '", "' . $_POST['p_id'] . '")';
    $res_addFavoriteItem = mysqli_query($connect, $sql_addFavoriteItem);
    if ($res_addFavoriteItem) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'code' => '500'));
    }
} else {
    echo json_encode(array('success' => false, 'code' => '401'));
}