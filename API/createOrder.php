<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['shipping_addr']) && isset($_GET['shipping_id']) && isset($_GET['payment_method']) && isset($_GET['shipping_price'])) {
        $sql_shipping_addr = 'SELECT * FROM address WHERE address_id = "' . mysqli_real_escape_string($connect, $_GET['shipping_addr']) . '"';
        $res_shipping_addr = mysqli_query($connect, $sql_shipping_addr);
        if ($res_shipping_addr) {
            if (mysqli_num_rows($res_shipping_addr) == 1) {
                $fetch_shipping_addr = mysqli_fetch_assoc($res_shipping_addr);
                $order_status = $_GET['payment_method'] == 'tranfer' ? 'pending' : 'processing';
                $sql_order = 'INSERT INTO `order` (`shipping_provider_id`, `shipping_price`, `address_id`, `first_name`, `last_name`, `phone`, `address`, `city`, `province`, `zip_code`, `u_id`, `status`, `payment_method`) VALUES ("' . $_GET['shipping_id'] . '", "' . $_GET['shipping_price'] . '", "' . $fetch_shipping_addr['address_id'] . '", "' . $fetch_shipping_addr['first_name'] . '", "' . $fetch_shipping_addr['last_name'] . '", "' . $fetch_shipping_addr['phone'] . '", "' . $fetch_shipping_addr['address'] . '", "' . $fetch_shipping_addr['city'] . '", "' . $fetch_shipping_addr['province'] . '", "' . $fetch_shipping_addr['zip_code'] . '", "' . $_SESSION['u_id'] . '", "' . $order_status . '", "' . $_GET['payment_method'] . '")';
                $res_order = mysqli_query($connect, $sql_order);
                if ($res_order) {
                    $sql_order_id = 'SELECT order_id FROM `order` WHERE shipping_provider_id = "' . $_GET['shipping_id'] . '" AND u_id = "' . $_SESSION['u_id'] . '" ORDER BY order_id DESC LIMIT 1';
                    $res_order_id = mysqli_query($connect, $sql_order_id);
                    if ($res_order_id) {
                        $fetch_order_id = mysqli_fetch_assoc($res_order_id);
                        echo json_encode(array('success' => true, 'code' => 200, 'orderId' => $fetch_order_id['order_id']));
                    } else {
                        echo json_encode(array('success' => false, 'code' => 500, 'reason' => 'ไม่สามารถหา orderId จากฐานข้อมูลได้'));
                    }
                } else {
                    echo json_encode(array('success' => false, 'code' => 500, 'reason' => 'สร้าง order ไม่สำเร็จ'));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10102, 'reason' => 'ไม่พบที่อยู่การจัดส่งนี้'));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 500));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
