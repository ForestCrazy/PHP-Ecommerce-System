<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isset($_POST['p_id'])) {
            if (isProductOfStore($_POST['p_id'], hasOwnStore($_SESSION['u_id'], true))) {
                $sql_clearShippingOfProduct = 'DELETE FROM product_shipping WHERE product_id = "' . mysqli_real_escape_string($connect, $_POST['p_id']) . '"';
                $res_clearShippingOfProduct = mysqli_query($connect, $sql_clearShippingOfProduct);
                if ($res_clearShippingOfProduct) {
                    echo json_encode(array('success' => true, 'code' => 200));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'สินค้านี้ไม่ได้อยู่ในร้านค้าที่คุณเป็นเจ้าของร้าน'));
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