<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isAdmin($_SESSION['u_id'])) {
        if (isset($_GET['banner_id'])) {
            $sql_remove_banner = 'DELETE FROM banner WHERE banner_id = "' . $_GET['banner_id'] . '"';
            $res_remove_banner = mysqli_query($connect, $sql_remove_banner);
            if ($res_remove_banner) {
                echo json_encode(array('success' => true, 'code' => 200));
            } else {
                echo json_encode(array('success' => false, 'code' => 500));
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