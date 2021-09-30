<?php
function gotoPage($pageName)
{
?>
    <script>
        window.location.href = '?page=<?= $pageName ?>';
    </script>
<?php
}

function number_abbr($number)
{
    $abbrevs = [12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => ''];

    foreach ($abbrevs as $exponent => $abbrev) {
        if (abs($number) >= pow(10, $exponent)) {
            $display = $number / pow(10, $exponent);
            $decimals = ($exponent >= 3 && round($display) < 100) ? 1 : 0;
            $number = number_format($display, $decimals) . $abbrev;
            break;
        }
    }

    return $number;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hasOwnStore($u_id, $reqStoreId = false)
{
    global $connect;
    $sql_check_hasStore = 'SELECT store_id FROM store WHERE u_id = "' . $u_id . '"';
    $res_check_store = mysqli_query($connect, $sql_check_hasStore);
    if ($res_check_store) {
        if (mysqli_num_rows($res_check_store) == 1) {
            return $reqStoreId ? mysqli_fetch_assoc($res_check_store)['store_id'] : true;
        } else {
            return false;
        }
    }
}

function isProductOfStore($p_id, $store_id)
{
    global $connect;
    $sql_check_product = 'SELECT product_id FROM product WHERE product_id = "' . mysqli_real_escape_string($connect, $p_id) . '" AND store_id = "' . mysqli_real_escape_string($connect, $store_id) . '"';
    $res_check_product = mysqli_query($connect, $sql_check_product);
    if ($res_check_product) {
        if (mysqli_num_rows($res_check_product) == 1) {
            return true;
        } else {
            return false;
        }
    }
}

function createSubOrder($order_id, $p_id, $p_qty)
{
    global $connect;
    $sql_product = 'SELECT product_price, product_quantity FROM product WHERE product_id = "' . $p_id . '"';
    $res_product = mysqli_query($connect, $sql_product);
    if ($res_product) {
        if (mysqli_num_rows($res_product) == 1) {
            $fetch_product = mysqli_fetch_assoc($res_product);
            if ($fetch_product['product_quantity'] >= 1) {
                $sql_update_product = 'UPDATE product SET product_quantity = product_quantity - ' . $p_qty . ' WHERE product_id = "' . $p_id . '"';
                $res_update_product = mysqli_query($connect, $sql_update_product);
                if ($res_update_product) {
                    $sql_sub_order = 'INSERT INTO sub_order (order_id, product_id, quantity, total_price) VALUES ("' . $order_id . '", "' . $p_id . '", "' . $p_qty . '", "' . $p_qty * $fetch_product['product_price'] . '")';
                    $res_sub_order = mysqli_query($connect, $sql_sub_order);
                    if ($res_sub_order) {
                        return json_encode(array('success' => true, 'code' => 200, 'orderId' => $order_id));
                    } else {
                        $sql_update_product = 'UPDATE product SET product_quantity = product_quantity + ' . $p_qty . ' WHERE product_id = "' . $p_id . '"';
                        $res_update_product = mysqli_query($connect, $sql_update_product);
                        if ($res_update_product) {
                            return json_encode(array('success' => false, 'code' => 500, 'reason' => 'เกิดข้อผิดพลาดในการเพิ่ม sub_order'));
                        } else {
                            return json_encode(array('success' => false, 'code' => 500, 'reason' => 'เกิดข้อผิดพลาดในการเพิ่ม sub_order', 'sub_code' => 500, 'sub_reason' => 'เกิดข้อผิดพลาดในการย้อนคืนจำนวนสินค้า'));
                        }
                    }
                } else {
                    return json_encode(array('success' => false, 'code' => 500, 'reason' => 'เกิดข้อผิดพลาดในการอัพเดทจำนวนสินค้า'));
                }
            } else {
                return json_encode(array('success' => false, 'code' => 10100, 'reason' => 'จำนวนสินค้าคงเหลือไม่พอสำหรับคำสั่งซื้อ'));
            }
        } else {
            return json_encode(array('success' => false, 'code' => 10102, 'reason' => 'ไม่พบสินค้าชิ้นนี้ในฐานข้อมูล'));
        }
    } else {
        return json_encode(array('success' => false, 'code' => 500));
    }
}

function createOrder($shipping_addr, $shipping_id, $payment_method, $shipping_price, $cart_id = null)
{
    global $connect;
    $sql_shipping_addr = 'SELECT * FROM address WHERE address_id = "' . mysqli_real_escape_string($connect, $shipping_addr) . '" AND u_id = "' . $_SESSION['u_id'] . '"';
    $res_shipping_addr = mysqli_query($connect, $sql_shipping_addr);
    if ($res_shipping_addr) {
        if (mysqli_num_rows($res_shipping_addr) == 1) {
            $fetch_shipping_addr = mysqli_fetch_assoc($res_shipping_addr);
            $order_status = $payment_method == 'transfer' ? 'pending' : 'processing';
            $sql_order = 'INSERT INTO `order` (`shipping_provider_id`, `shipping_price`, `address_id`, `first_name`, `last_name`, `phone`, `address`, `city`, `province`, `zip_code`, `u_id`, `status`, `payment_method`) VALUES ("' . $shipping_id . '", "' . $shipping_price . '", "' . $fetch_shipping_addr['address_id'] . '", "' . $fetch_shipping_addr['first_name'] . '", "' . $fetch_shipping_addr['last_name'] . '", "' . $fetch_shipping_addr['phone'] . '", "' . $fetch_shipping_addr['address'] . '", "' . $fetch_shipping_addr['city'] . '", "' . $fetch_shipping_addr['province'] . '", "' . $fetch_shipping_addr['zip_code'] . '", "' . $_SESSION['u_id'] . '", "' . $order_status . '", "' . $payment_method . '")';
            $res_order = mysqli_query($connect, $sql_order);
            if ($res_order) {
                return json_encode(array('success' => true, 'code' => 200, 'orderId' => mysqli_insert_id($connect)));
            } else {
                return json_encode(array('success' => false, 'code' => 500, 'reason' => 'สร้าง order ไม่สำเร็จ'));
            }
        } else {
            return json_encode(array('success' => false, 'code' => 10102, 'reason' => 'ไม่พบที่อยู่การจัดส่งนี้'));
        }
    } else {
        return json_encode(array('success' => false, 'code' => 500));
    }
}

function isAdmin($user_id)
{
    global $connect;
    $sql_user = 'SELECT rank FROM user WHERE u_id = "' . $user_id . '"';
    $res_user = mysqli_query($connect, $sql_user);
    if ($res_user) {
        if (mysqli_num_rows($res_user) == 1) {
            $fetch_user = mysqli_fetch_assoc($res_user);
            if ($fetch_user['rank'] == 'admin') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
