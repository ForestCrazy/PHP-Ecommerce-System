<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isAdmin($_SESSION['u_id'])) {
        if (isset($_POST['banner_tier']) && isset($_FILES['banner']) && isset($_POST['banner_alt'])) {
            $timestamp = time();
            if (move_uploaded_file($_FILES['banner']['tmp_name'], '../asset/img/banner/' . $timestamp . '-' . $_FILES['banner']['name'])) {
                $filename = '/asset/img/banner/' . $timestamp . '-' . $_FILES['banner']['name'];
                $sql_insert_banner = 'INSERT INTO banner (banner_tier, banner_img, banner_alt) VALUES ("' . $_POST['banner_tier'] . '", "' . $filename . '", "' . $_POST['banner_alt'] . '")';
                $res_insert_banner = mysqli_query($connect, $sql_insert_banner);
                if ($res_insert_banner) {
                    echo json_encode(array('success' => true, 'code' => 200));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
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
