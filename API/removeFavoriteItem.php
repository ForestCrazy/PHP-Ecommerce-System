<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_removeFavoriteItem = 'DELETE FROM product_favorite WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
    $res_removeFavoriteItem = mysqli_query($connect, $sql_removeFavoriteItem);
    if ($res_removeFavoriteItem) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'code' => '500'));
    }
} else {
    echo json_encode(array('success' => false, 'code' => '401'));
}