<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isAdmin($_SESSION['u_id'])) {
        if (isset($_POST['banner_id']) && isset($_FILES['banner']) && isset($_POST['banner_tier']) && isset($_POST['banner_alt'])) {
            $sql_banner = 'SELECT * FROM banner WHERE banner_id = "' . $_POST['banner_id'] . '"';
            $res_banner = mysqli_query($connect, $sql_banner);
            if ($res_banner) {
                if (mysqli_num_rows($res_banner) == 1) {
                    $fetch_banner = mysqli_fetch_assoc($res_banner);
                    $timestamp = time();
                    if (move_uploaded_file($_FILES['banner']['tmp_name'], '../asset/img/banner/' . $timestamp . '-' . $_FILES['banner']['name'])) {
                        $filename = '/asset/img/banner/' . $timestamp . '-' . $_FILES['banner']['name'];
                        $sql_update_banner = 'UPDATE banner SET banner_tier = "' . $_POST['banner_tier'] . '", banner_img = "' . $filename . '", banner_alt = "' . $_POST['banner_alt'] . '" WHERE banner_id = "' . $_POST['banner_id'] . '"';
                        $res_update_banner = mysqli_query($connect, $sql_update_banner);
                        if ($res_update_banner) {
                            unlink('..' . $fetch_banner['banner_img']);
                            echo json_encode(array('success' => true, 'code' => 200));
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 10102));
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