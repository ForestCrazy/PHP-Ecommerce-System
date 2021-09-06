<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_POST['p_id'])) {
        $sql_shippingProduct = 'SELECT * FROM product_shipping WHERE product_id = "' . $_POST['p_id'] . '"';
        $res_shippingProduct = mysqli_query($connect, $sql_shippingProduct);
        if ($res_shippingProduct) {
            if (mysqli_num_rows($res_shippingProduct) > 0) {
                $sql_delShippingProduct = 'DELETE FROM product_shipping WHERE product_id = "' . $_POST['p_id'] . '"';
                $res_delShippingProduct = mysqli_query($connect, $sql_delShippingProduct);
                if ($res_delShippingProduct) {
                    die(json_encode(array('success' => false, 'code' => 500, 'reason' => 'ลบข้อมูลเก่าไม่สำเร็จ')));
                }
            }
            for ($i = 0; $i < count($_POST['shippingProvider']); $i++) {
                $sql_addShippingProduct = 'INSERT INTO product_shipping (product_id, shipping_provider_id, shipping_price, shipping_time) VALUES ("' . $_POST['p_id'] . '", "' . $_POST['shippingProvider'][$i] . '", "' . $_POST['shippingPrice'][$i] . '", "' . $_POST['shippingTime'][$i] . '")';
                $res_addShippingProduct = mysqli_query($connect, $sql_addShippingProduct);
            }
            echo json_encode(array('success' => true, 'code' => 200));
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
