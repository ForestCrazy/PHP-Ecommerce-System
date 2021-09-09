<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isset($_POST['productId'])) {
            $sql_imgProduct = 'SELECT * FROM product_img WHERE product_id = "' . $_POST['productId'] . '"';
            $res_imgProduct = mysqli_query($connect, $sql_imgProduct);
            if ($res_imgProduct) {
                if (mysqli_num_rows($res_imgProduct) > 0) {
                    $sql_clearImgProduct = 'DELETE FROM product_img WHERE product_id = "' . $_POST['productId'] . '"';
                    $res_clearImgProduct = mysqli_query($connect, $sql_clearImgProduct);
                    if ($res_clearImgProduct) {
                        while ($fetch_imgProduct = mysqli_fetch_assoc($res_imgProduct)) {
                            unlink('..' . $fetch_imgProduct['img_url']);
                        }
                    } else {
                        die(json_encode(array('success' => false, 'code' => 500, 'reason' => 'ลบข้อมูลเก่าไม่สำเร็จ')));
                    }
                }
                if (isset($_FILES['productImage'])) {
                    for ($i = 0; $i < count($_FILES['productImage']['name']); $i++) {
                        $timestamp = time();
                        if (move_uploaded_file($_FILES['productImage']['tmp_name'][$i], '../asset/img/product_img/' . $timestamp . '-' . $_FILES['productImage']['name'][$i])) {
                            $file_name = '/asset/img/product_img/' . $timestamp . '-' . $_FILES['productImage']['name'][$i];
                            $sql_ProductImage = 'INSERT INTO product_img (`product_id`, `weight`, `img_url`) VALUES ("' . $_POST['productId'] . '", "' . $i . '", "' . $file_name . '")';
                            $res_ProductImage = mysqli_query($connect, $sql_ProductImage);
                        }
                    }
                    echo json_encode(array('success' => true, 'code' => 200));
                } else {
                    echo json_encode(array('success' => false, 'code' => 10001, 'reason' => 'ไม่พบรูปภาพสินค้าที่ทำการอัพโหลดมา'));
                }
            } else {
                echo json_encode(array('success' => false, 'code' => 500, 'reason' => 'เกิดข้อผิดพลาดในการเช็คข้อมูล'));
            }
        } else {
            echo json_encode(array('success' => false, 'code' => 10001, 'reason' => 'ไม่พบหมายเลขสินค้าที่ต้องการอัพเดทรูปภาพสินค้า'));
        }
    } else {
        echo json_encode(array('success' => false, 'code' => 403));
    }
} else {
    echo json_encode(array('success' => false, 'code' => 401));
}
