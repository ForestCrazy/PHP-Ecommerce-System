<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['p_id'])) {
        if (hasOwnStore($_SESSION['u_id'])) {
            if (isProductOfStore($_GET['p_id'], hasOwnStore($_SESSION['u_id'], true))) {
                $sql_imgProduct = 'SELECT * FROM product_img WHERE product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
                $res_imgProduct = mysqli_query($connect, $sql_imgProduct);
                if ($res_imgProduct) {
                    echo json_encode(array('success' => true, 'code' => 200, 'data' => mysqli_fetch_all($res_imgProduct, MYSQLI_ASSOC)));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 403, 'store_id' => hasOwnStore($_SESSION['u_id'], true)));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 403));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10010));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
