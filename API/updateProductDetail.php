<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isProductOfStore($_POST['productId'], hasOwnStore($_SESSION['u_id'], true))) {
            $sql_updateProduct = 'UPDATE product SET product_name = "' . $_POST['productName'] . '", product_description = "' . $_POST['productDescription'] . '", product_quantity = "' . $_POST['productQty'] . '", product_price = "' . $_POST['productPrice'] . '" WHERE product_id = "' . $_POST['productId'] . '"';
            $res_updateProduct = mysqli_query($connect, $sql_updateProduct);
            if ($sql_updateProduct) {
                echo json_encode(array('success' => true, 'code' => 200));
            } else {
                echo json_encode(array('success' => false, 'code' => 500));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 403));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 403));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}