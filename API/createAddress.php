<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_createAddress = 'INSERT INTO address (first_name, last_name, phone, address, city, province, zip_code, u_id) VALUES ("' . $_POST['firstname'] . '", "' . $_POST['lastname'] . '", "' . $_POST['phone'] . '", "' . $_POST['address'] . '", "' . $_POST['city'] . '", "' . $_POST['province'] . '", "' . $_POST['zip_code'] . '", "' . $_SESSION['u_id'] . '")';
    $res_createAddress = mysqli_query($connect, $sql_createAddress);
    if ($res_createAddress) {
        echo json_encode(array('success' => true, 'code' => 200));
    } else {
        echo json_encode(array('success' => false, 'code' => 500));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
