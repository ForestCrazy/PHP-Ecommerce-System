<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_POST['store_name']) && isset($_POST['store_description']) && isset($_POST['store_address']) && isset($_POST['store_city']) && isset($_POST['store_province']) && isset($_POST['store_zip_code'])) {
        if (hasOwnStore($_SESSION['u_id'])) {
            $sql_update_store = 'UPDATE store SET store_name = "' . $_POST['store_name'] . '", store_description = "' . $_POST['store_description'] . '", store_city = "' . $_POST['store_city'] . '", store_province = "' . $_POST['store_province'] . '", store_zip_code = "' . $_POST['store_zip_code'] . '" WHERE store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND u_id = "' . $_SESSION['u_id'] . '"';
            $res_update_store = mysqli_query($connect, $sql_update_store);
            if ($res_update_store) {
                echo json_encode(array('success' => true, 'code' => 200));
            } else {
                echo json_encode(array('success' => false, 'code' => 500));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 403));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}