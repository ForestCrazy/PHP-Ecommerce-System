<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    try {
        $sql_check_addToCart = 'SELECT quantity FROM cart WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
        $res_check_addToCart = mysqli_query($connect, $sql_check_addToCart);
        if ($res_check_addToCart) {
            $fetch_ItemInCart = mysqli_fetch_assoc($res_check_addToCart);
            $sql_product = 'SELECT product_quantity FROM product WHERE product_id = "' . $_GET['p_id'] . '"';
            $res_product = mysqli_query($connect, $sql_product);
            if ($res_product) {
                $fetch_product = mysqli_fetch_assoc($res_product);
                if ($fetch_product['product_quantity'] > $fetch_ItemInCart['quantity']) {
                    if (mysqli_num_rows($res_check_addToCart) == 0) {
                        $sql_addToCart = 'INSERT INTO cart (u_id, product_id, quantity, shipping_id) VALUES ("' . $_SESSION['u_id'] . '", "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '", "' . $_GET['quantity'] . '", "' . mysqli_real_escape_string($connect, $_GET['shipping_id']) . '")';
                        $res_addToCart = mysqli_query($connect, $sql_addToCart);
                        if ($res_addToCart) {
                            echo json_encode(array('success' => true, 'code' => 201));
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    } else {
                        $sql_addToCart = 'UPDATE cart SET quantity = quantity + ' . intval($_GET['quantity']) . ' WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
                        $res_addToCart = mysqli_query($connect, $sql_addToCart);
                        if ($res_addToCart) {
                            echo json_encode(array('success' => true, 'code' => 201));
                        } else {
                            echo json_encode(array('success' => false, 'code' => 500));
                        }
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'ไม่สามารถเพิ่มจำนวนสินค้าได้มากกว่าจำนวนสินค้าที่มี'));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 500));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } catch (Exception $e) {
        echo json_encode(array('success' => false, 'code' => 500, 'err' => $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
