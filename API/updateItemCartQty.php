<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    $sql_product = 'SELECT product_quantity FROM product WHERE product_id = "' . $_GET['p_id'] . '"';
    $res_product = mysqli_query($connect, $sql_product);
    if ($res_product) {
        $fetch_product = mysqli_fetch_assoc($res_product);
        $sql_itemQtyInCart = 'SELECT quantity FROM cart WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
        if ($_GET['operator'] == '-') {
            if (intval($_GET['qty']) == 1) {
                $sql_removeItemFromCart = 'DELETE FROM cart WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
                $res_removeItemFromCart = mysqli_query($connect, $sql_removeItemFromCart);
                if ($res_removeItemFromCart) {
                    echo json_encode(array('success' => true, 'code' => 204));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                $sql_addItemQtyInCart = 'UPDATE cart SET quantity = quantity - 1 WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
                $res_addItemQtyInCart = mysqli_query($connect, $sql_addItemQtyInCart);
                if ($res_addItemQtyInCart) {
                    $res_itemQtyInCart = mysqli_query($connect, $sql_itemQtyInCart);
                    if ($res_itemQtyInCart) {
                        $fetch_itemQtyInCart = mysqli_fetch_assoc($res_itemQtyInCart);
                        echo json_encode(array('success' => true, 'itemInCartQty' => intval($fetch_itemQtyInCart['quantity']), 'productQty' => intval($fetch_product['product_quantity'])));
                    } else {
                        echo json_encode(array('success' => true, 'itemInCartQty' => intval($_GET['qty']) - 1, 'productQty' => intval($fetch_product['product_quantity'])));
                    }
                } else {
                    echo json_encode(array('success' => false, 'itemInCartQty' => intval($_GET['qty']), 'productQty' => intval($fetch_product['product_quantity'])));
                }
            }
        } else if ($_GET['operator'] == '+') {
            if (intval($_GET['qty']) < $fetch_product['product_quantity']) {
                $sql_addItemQtyInCart = 'UPDATE cart SET quantity = quantity + 1 WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
                $res_addItemQtyInCart = mysqli_query($connect, $sql_addItemQtyInCart);
                if ($res_addItemQtyInCart) {
                    $res_itemQtyInCart = mysqli_query($connect, $sql_itemQtyInCart);
                    if ($res_itemQtyInCart) {
                        $fetch_itemQtyInCart = mysqli_fetch_assoc($res_itemQtyInCart);
                        echo json_encode(array('success' => true, 'itemInCartQty' => intval($fetch_itemQtyInCart['quantity']), 'productQty' => intval($fetch_product['product_quantity'])));
                    } else {
                        echo json_encode(array('success' => true, 'itemInCartQty' => intval($_GET['qty']) + 1, 'productQty' => intval($fetch_product['product_quantity'])));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 500, 'itemInCartQty' => intval($_GET['qty']), 'productQty' => intval($fetch_product['product_quantity'])));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => '10100', 'reason' => 'ไม่สามารถเพิ่มจำนวนสินค้าในรถเข็นได้มากกว่าจำนวนที่มี', 'itemInCartQty' => intval($_GET['qty']), 'productQty' => intval($fetch_product['product_quantity'])));
            }
        } else if ($_GET['operator'] == '=') {
            if (intval($_GET['qty']) <= $fetch_product['product_quantity']) {
                $sql_setItemQtyInCart = 'UPDATE cart SET quantity = "' . intval($_GET['qty']) . '" WHERE u_id = "' . $_SESSION['u_id'] . '" AND product_id = "' . $_GET['p_id'] . '"';
                $res_setItemQtyInCart = mysqli_query($connect, $sql_setItemQtyInCart);
                if ($res_setItemQtyInCart) {
                    echo json_encode(array('success' => true, 'code' => 200, 'itemInCartQty' => intval($_GET['qty']), 'productQty' => intval($fetch_product['product_quantity'])));
                } else {
                    $res_itemQtyInCart = mysqli_query($connect, $sql_itemQtyInCart);
                    if ($res_itemQtyInCart) {
                        echo json_encode(array('success' => false, 'code' => 500, 'itemInCartQty' => intval($fetch_itemQtyInCart['quantity']), 'productQty' => intval($fetch_product['product_quantity'])));
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500, 'itemInCartQty' => 1, 'productQty' => intval($fetch_product['product_quantity'])));
                    }
                }
            } else {
                $res_itemQtyInCart = mysqli_query($connect, $sql_itemQtyInCart);
                if ($res_itemQtyInCart) {
                    echo json_encode(array('success' => false, 'code' => 10100, 'reason' => 'ไม่สามารถเพิ่มจำนวนสินค้าในรถเข็นได้มากกว่าจำนวนที่มี', 'itemInCartQty' => intval($fetch_itemQtyInCart['quantity']), 'productQty' => intval($fetch_product['product_quantity'])));
                } else {
                    echo json_encode(array('success' => false, 'code' => 10100, 'sub_code' => 500, 'reason' => 'ไม่สามารถเพิ่มจำนวนสินค้าในรถเข็นได้มากกว่าจำนวนที่มี', 'itemInCartQty' => 1, 'productQty' => intval($fetch_product['product_quantity'])));
                }
            }
        } else {
            $res_itemQtyInCart = mysqli_query($connect, $sql_itemQtyInCart);
            if ($res_itemQtyInCart) {
                echo json_encode(array('success' => false, 'code' => 400, 'itemInCartQty' => intval($fetch_itemQtyInCart['quantity']), 'productQty' => intval($fetch_product['product_quantity'])));
            } else {
                echo json_encode(array('success' => false, 'code' => 400, 'sub_code' => 500, 'itemInCartQty' => 1, 'productQty' => intval($fetch_product['product_quantity'])));
            }
        }
    } else {
        echo json_encode(array('success' => false, 'itemInCartQty' => intval($_GET['qty'])));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
