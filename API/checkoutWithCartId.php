<?php
require('../system/database.php');
require('../system/oop.php');

if ($_SESSION['username']) {
    if (isset($_POST['cart_id']) && isset($_POST['shipping_addr']) && isset($_POST['payment_method'])) {
        $sql_store = 'SELECT store.store_id, store.store_name FROM cart_temp INNER JOIN product ON cart_temp.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id WHERE cart_temp.cart_id = "' . $_POST['cart_id'] . '" GROUP BY store.store_id';
        $res_store = mysqli_query($connect, $sql_store);
        if ($res_store) {
            $order_arr = [];
            while ($fetch_store = mysqli_fetch_assoc($res_store)) {
                $sql_shipping_store = 'SELECT cart.shipping_id FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id WHERE cart_temp.cart_id = "' . $_POST['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" GROUP BY cart.shipping_id';
                $res_shipping_store = mysqli_query($connect, $sql_shipping_store);
                if ($res_shipping_store) {
                    while ($fetch_shipping_store = mysqli_fetch_assoc($res_shipping_store)) {
                        $sql_shipping = 'SELECT MAX(product_shipping.shipping_price) AS shipping_price FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN product_shipping ON product.product_id = product_shipping.product_id AND cart.shipping_id = product_shipping.shipping_provider_id INNER JOIN shipping_provider ON product_shipping.shipping_provider_id = shipping_provider.shipping_provider_id WHERE cart_temp.cart_id = "' . $_POST['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                        $res_shipping = mysqli_query($connect, $sql_shipping);
                        if ($res_shipping) {
                            $fetch_shipping = mysqli_fetch_assoc($res_shipping);
                            $order = createOrder($_POST['shipping_addr'], $fetch_shipping_store['shipping_id'], $_POST['payment_method'], $fetch_shipping['shipping_price'], $_POST['cart_id']);
                            $order_obj = json_decode($order);
                            if ($order_obj->success) {
                                $sql_product_checkout = 'SELECT product.*, cart.*, shipping_provider.shipping_name, product_img.img_url FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN shipping_provider ON cart.shipping_id = shipping_provider.shipping_provider_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE cart_temp.cart_id = "' . $_POST['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                                $res_product_checkout = mysqli_query($connect, $sql_product_checkout);
                                if ($res_product_checkout) {
                                    $suborder_arr = [];
                                    while ($fetch_product_checkout = mysqli_fetch_assoc($res_product_checkout)) {
                                        $suborder = createSubOrder($order_obj->orderId, $fetch_product_checkout['product_id'], $fetch_product_checkout['quantity']);
                                        array_push($suborder_arr, $suborder);
                                        $suborder_obj = json_decode($suborder);
                                        if ($suborder_obj->success) {
                                            $sql_delete_item = 'DELETE FROM cart WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $fetch_product_checkout['product_id'] . '"';
                                            $res_delete_item = mysqli_query($connect, $sql_delete_item);
                                        }
                                    }
                                    array_push($order_arr, array('order' => $order, 'suborder' => $suborder_arr));
                                } else {
                                    array_push($order_arr, array('order' => $order, 'suborder' => []));
                                }
                            } else {
                                array_push($order_arr, array('order' => $order, 'suborder' => []));
                            }
                        }
                    }
                }
            }
            echo json_encode(array('success' => true, 'code' => 200, 'orderRes' => $order_arr));
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
