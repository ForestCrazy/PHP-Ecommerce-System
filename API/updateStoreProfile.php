<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isset($_FILES['store_profile'])) {
            $sql_store_img = 'SELECT store_img FROM store WHERE store_id = "' . hasOwnStore($_SESSION['u_id']) . '" AND u_id = "' . $_SESSION['u_id'] . '"';
            $res_store_img = mysqli_query($connect, $sql_store_img);
            if ($res_store_img) {
                if (mysqli_num_rows($res_store_img) == 1) {
                    $fetch_store_img = mysqli_fetch_assoc($res_store_img);
                    $timestamp = time();
                    if (move_uploaded_file($_FILES['store_profile']['tmp_name'], '../asset/img/store_profile/' . $timestamp . '-' . $_FILES['store_profile']['name'])) {
                        $store_profile = '/asset/img/store_profile/' . $timestamp . '-' . $_FILES['store_profile']['name'];
                        $sql_update_store = 'UPDATE store SET store_img = "' . $store_profile . '" WHERE store_id = "' . hasOwnStore($_SESSION['u_id']) . '" AND u_id = "' . $_SESSION['u_id'] . '"';
                        $res_update_store = mysqli_query($connect, $sql_update_store);
                        if ($res_update_store) {
                            if (!empty($fetch_store_img['store_img'])) {
                                try {
                                    unlink('..' . $fetch_store_img['store_img']);
                                    echo json_encode(array('success' => true, 'code' => 200));
                                } catch (Exception $e) {
                                    echo json_encode(array('success' => true, 'code' => 200, 'sub_code' => 500));
                                }
                            } else {
                                echo json_encode(array('success' => true, 'code' => 200));
                            }
                        } else {
                            unlink('..' . $store_profile);
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
