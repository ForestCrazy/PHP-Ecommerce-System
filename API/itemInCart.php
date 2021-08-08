<?php
require('../system/database.php');
require('../system/oop.php');

$sql_cart = 'SELECT SUM(quantity) AS amountItemInCart FROM cart INNER JOIN product ON cart.product_id = product.product_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id INNER JOIN product_shipping ON product.product_id = product_shipping.product_id AND cart.shipping_id = product_shipping.shipping_provider_id WHERE cart.u_id = "' . $_SESSION['u_id'] . '"';
$res_cart = mysqli_query($connect, $sql_cart);
if ($res_cart) {
    // $res_array = array();
    // $res_array['success'] = true;
    // $res_array['code'] = 200;
    // $res_array['itemList'] = array();
    // while ($fetch_cart = mysqli_fetch_assoc($res_cart)) {
    //     array_push($res_array['itemList'], $fetch_cart);
    // }
    // echo json_encode($res_array);
    echo json_encode(array('success' => true, 'code' => 200, 'amountItemInCart' => mysqli_fetch_assoc($res_cart)['amountItemInCart']));
} else {
    echo json_encode(array('success' => false, 'code' => 500));
}
