<?php
require('../system/database.php');
require('../system/oop.php');

if ($_SESSION['username']) {
    if (isset($_POST['cart_id'])) {
        $sql_store = 'SELECT store.store_id, store.store_name FROM cart_temp INNER JOIN product ON cart_temp.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" GROUP BY store.store_id';
        $res_store = mysqli_query($connect, $sql_store);
        if ($res_store) {
            while ($fetch_store = mysqli_fetch_assoc($res_store)) {
                $sql_shipping_store = 'SELECT cart.shipping_id FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" GROUP BY cart.shipping_id';
                $res_shipping_store = mysqli_query($connect, $sql_shipping_store);
                if ($res_shipping_store) {
                    $sql_shipping = 'SELECT product.store_id, cart.shipping_id, shipping_provider.shipping_name, MAX(product_shipping.shipping_price) AS shipping_price FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN product_shipping ON product.product_id = product_shipping.product_id AND cart.shipping_id = product_shipping.shipping_provider_id INNER JOIN shipping_provider ON product_shipping.shipping_provider_id = shipping_provider.shipping_provider_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                    $res_shipping = mysqli_query($connect, $sql_shipping);
                    if ($res_shipping) {
                        $fetch_shipping = mysqli_fetch_assoc($res_shipping);
                        while ($fetch_shipping_store = mysqli_fetch_assoc($res_shipping_store)) {
                            $sql_product_checkout = 'SELECT product.*, cart.*, shipping_provider.shipping_name, product_img.img_url FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN shipping_provider ON cart.shipping_id = shipping_provider.shipping_provider_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                            $res_product_checkout = mysqli_query($connect, $sql_product_checkout);
                            if ($res_product_checkout) {
                                while ($fetch_product_checkout = mysqli_fetch_assoc($res_product_checkout)) {
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
