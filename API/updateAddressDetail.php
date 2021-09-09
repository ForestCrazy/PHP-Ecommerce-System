<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    try {
        $sql_updateAddress = 'UPDATE address SET first_name = "' . $_POST['firstname'] . '", last_name = "' . $_POST['lastname'] . '", address = "' . $_POST['address'] . '", city = "' . $_POST['city'] . '", province = "' . $_POST['province'] . '", zip_code = "' . $_POST['zip_code'] . '" WHERE address_id = "' . $_POST['address_id'] . '" AND u_id = "' . $_SESSION['u_id'] . '"';
    } catch (Exception $e) {
        die(json_encode(array('success' => false, 'code' => 10001)));
    }
    $res_updateAddress = mysqli_query($connect, $sql_updateAddress);
    if ($res_updateAddress) {
        echo json_encode(array('success' => true, 'code' => 200));
    } else {
        echo json_encode(array('success' => false, 'code' => 500));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
