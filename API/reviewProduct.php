<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (isset($_GET['sub_order_id']) && isset($_GET['score_product']) && isset($_GET['msg_review'])) {
        $sql_check_sub_order = 'SELECT order.order_id FROM sub_order INNER JOIN `order` ON sub_order.order_id = order.order_id WHERE order.u_id = "' . $_SESSION['u_id'] . '" AND sub_order.sub_order_id = "' . $_GET['sub_order_id'] . '"';
        $res_check_sub_order = mysqli_query($connect, $sql_check_sub_order);
        if ($res_check_sub_order) {
            if (mysqli_num_rows($res_check_sub_order) == 1) {
                $sql_product_review = 'INSERT INTO product_review (sub_order_id, star_score, review_msg) VALUES ("' . $_GET['sub_order_id'] . '", "' . $_GET['score_product'] . '", "' . $_GET['msg_review'] . '")';
                $res_product_review = mysqli_query($connect, $sql_product_review);
                if ($res_product_review) {
                    echo json_encode(array('success' => true, 'code' => 200));
                } else {
                    echo json_encode(array('success' => false, 'code' => 500));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 10102, 'reason' => 'ไม่พบ sub_order_id นี้ในฐานข้อมูล'));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 500, 'sql' => $sql_check_sub_order));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 10001));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}