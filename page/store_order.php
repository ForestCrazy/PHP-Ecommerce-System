<?php
if (!isset($_SESSION['username'])) {
?>
    <script>
        window.location.href = '?page=login';
    </script>
    <?php
} else {
    if (!$user_info['hasStore']) {
    ?>
        <script>
            window.location.href = '?page=home';
        </script>
        <?php
    } else {
        if (isset($_GET['order_id'])) {
            $sql_order_id = 'SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status != "pending" AND  order.order_id = "' . $_GET['order_id'] . '" GROUP BY order.order_id ORDER BY order.order_id DESC';
            $res_order_id = mysqli_query($connect, $sql_order_id);
            if ($res_order_id) {
                if (mysqli_num_rows($res_order_id) == 1) {
                    $fetch_order_id = mysqli_fetch_assoc($res_order_id);
                    $sql_order = 'SELECT *, CAST(sub_order.total_price / sub_order.quantity AS INT) AS price_per_item, order.product_price AS total_product_price FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id INNER JOIN shipping_provider ON order.shipping_provider_id = shipping_provider.shipping_provider_id WHERE order.order_id = "' . $fetch_order_id['order_id'] . '"';
                    $res_order = mysqli_query($connect, $sql_order);
                    if ($res_order) {
                        $loop = mysqli_num_rows($res_order);
                        $current_loop = 0;
        ?>
                        <script>
                            function updateTrackCode() {
                                var url = new URL(window.location.href);
                                var order_id = url.searchParams.get('order_id');
                                if (order_id) {
                                    var track_code = $('#track-code').val();
                                    if (track_code) {
                                        Swal.fire({
                                            title: 'แน่ใจหรือไม่ที่จะทำการอัพเดทหมายเลขติดตามสินค้า',
                                            text: "หมายเลขติดตามพัสดุคือ : " + track_code,
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'ใช่ ยืนยัน',
                                            cancelButtonText: 'ไม่ ยกเลิก'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $.get('/API/updateTrackCode.php', {
                                                    order_id: order_id,
                                                    track_code: track_code
                                                }, function(res) {
                                                    var resp = JSON.parse(res);
                                                    if (resp.success) {
                                                        location.reload();
                                                    } else {
                                                        console.error(resp.error ? resp.error : 'failed to update track code');
                                                    }
                                                })
                                            }
                                        })
                                    }
                                }
                            }
                        </script>
                        <div class='col col-top'>
                            <span class='font-weight-bold'>
                                รายการสินค้า
                            </span>
                            <div class='card'>
                                <?php
                                while ($fetch_order = mysqli_fetch_assoc($res_order)) {
                                    if ($current_loop == 0) {
                                ?>
                                        <div class='col-top'>
                                            <div class='d-flex justify-content-start align-items-center'>
                                                <div class='text-nowrap' style='width: 120px;'>
                                                    &emsp;
                                                    #<?= $fetch_order['order_id'] ?> | <?= $fetch_order['createtime'] ?>
                                                </div>
                                                <div class='flex-fill'>
                                                    <div class='row'>
                                                        <div class='col-4'></div>
                                                        <div class='col-8 text-center d-none d-md-block'>
                                                            <div class='row'>
                                                                <div class='col'>
                                                                    จำนวน
                                                                </div>
                                                                <div class='col'>
                                                                    รวม
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                        ?>
                                        <div class='d-flex justify-content-start align-items-center'>
                                            <img src='<?= $fetch_order['img_url'] ?>' style='max-height: 120px; max-width: 120px;' class='img-fluid' />
                                            <div class='flex-fill'>
                                                <div class='row'>
                                                    <div class='col-md-4'>
                                                        <span>
                                                            <?= $fetch_order['product_name'] ?>
                                                        </span>
                                                    </div>
                                                    <div class='col-md-8 text-md-center'>
                                                        <div class='row'>
                                                            <div class='col-sm-6'>
                                                                <span class='d-inline d-md-none'>
                                                                    จำนวน
                                                                </span>
                                                                <span class='d-inline d-md-none'>
                                                                    :
                                                                </span>
                                                                <span class='d-inline d-md-block'>
                                                                    <?= $fetch_order['quantity'] ?>
                                                                </span>
                                                            </div>
                                                            <div class='col-sm-6'>
                                                                <span class='d-inline d-md-none'>
                                                                    รวม
                                                                </span>
                                                                <span class='d-inline d-md-none'>
                                                                    :
                                                                </span>
                                                                <span class='d-inline d-md-block'>
                                                                    ฿<?= $fetch_order['total_price'] ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $current_loop += 1;
                                        if ($current_loop == $loop) {
                                        ?>
                                        </div>
                                <?php
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                        $res_order = mysqli_query($connect, $sql_order);
                        if ($res_order) {
                            $fetch_order = mysqli_fetch_assoc($res_order);
                        ?>
                            <div class='col col-top'>
                                <span class='font-weight-bold'>
                                    การจัดส่ง
                                </span>
                                <div class='card p-2'>
                                    <span>
                                        จัดส่งโดย : <?= $fetch_order['shipping_name'] ?>
                                    </span>
                                    <span>
                                        ค่าจัดส่ง : ฿<?= $fetch_order['shipping_price'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class='col col-top'>
                                <span class='font-weight-bold'>
                                    ที่อยู่ในการจัดส่ง
                                </span>
                                <div class='card p-2'>
                                    <?= $fetch_order['first_name'] . ' ' . $fetch_order['last_name'] . ' ' . $fetch_order['phone'] . '&emsp;' . $fetch_order['address'] . ', อำเภอ ' . $fetch_order['city'] . ', จังหวัด ' . $fetch_order['province'] . ', รหัสไปรษณีย์ ' . $fetch_order['zip_code'] ?>
                                </div>
                            </div>
                            <div class='col col-top'>
                                <span class='font-weight-bold'>
                                    ช่องทางการชำระเงิน
                                </span>
                                <div class='card p-2'>
                                    <span>
                                        <?php
                                        if ($fetch_order['payment_method'] == 'transfer') {
                                            echo 'ชำระเงินผ่านธนาคาร';
                                        } else {
                                            echo 'ชำระเงินปลายทาง';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class='col col-top'>
                                <span class='font-weight-bold'>
                                    หมายเลขติดตามพัสดุ
                                </span>
                                <div class='card p-2'>
                                    <input type='text' class='form-control w-auto' style='margin: .375rem;' id='track-code' value='<?= $fetch_order['track_code'] ?>' <?= !empty($fetch_order['track_code']) || $fetch_order['status'] != 'processing' ? 'readonly' : '' ?> />
                                    <?php
                                    if (empty($fetch_order['track_code']) && $fetch_order['status'] == 'processing') {
                                    ?>
                                        <div class='btn btn-outline-success' onclick='updateTrackCode()'>บันทึก</div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    gotoPage('store_order');
                }
            }
        } else {
            $sql_order_id = 'SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status != "pending" GROUP BY order.order_id ORDER BY order.order_id DESC';
            $res_order_id = mysqli_query($connect, $sql_order_id);
            if ($res_order_id) {
                while ($fetch_order_id = mysqli_fetch_assoc($res_order_id)) {
                    $sql_order = 'SELECT *, CAST(sub_order.total_price / sub_order.quantity AS INT) AS price_per_item, order.product_price AS total_product_price FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE order.order_id = "' . $fetch_order_id['order_id'] . '"';
                    $res_order = mysqli_query($connect, $sql_order);
                    if ($res_order) {
                        $loop = mysqli_num_rows($res_order);
                        $current_loop = 0;
                        while ($fetch_order = mysqli_fetch_assoc($res_order)) {
                            if ($current_loop == 0) {
                            ?>
                                <div class='card col-top pt-3'>
                                    <div class='d-flex justify-content-start align-items-center'>
                                        <div class='text-nowrap' style='width: 120px;'>
                                            &emsp;
                                            #<?= $fetch_order['order_id'] ?> | <?= $fetch_order['createtime'] ?>
                                        </div>
                                        <div class='flex-fill'>
                                            <div class='row'>
                                                <div class='col-4'></div>
                                                <div class='col-8 text-center d-none d-md-block'>
                                                    <div class='row'>
                                                        <div class='col'>
                                                            ราคาต่อชิ้น
                                                        </div>
                                                        <div class='col'>
                                                            จำนวน
                                                        </div>
                                                        <div class='col'>
                                                            รวม
                                                        </div>
                                                        <div class='col'>
                                                            สถานะสินค้า
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                            }
                                ?>
                                <div class='d-flex justify-content-start align-items-center'>
                                    <img src='<?= $fetch_order['img_url'] ?>' style='max-height: 120px; max-width: 120px;' class='img-fluid' />
                                    <div class='flex-fill'>
                                        <div class='row'>
                                            <div class='col-md-4'>
                                                <span>
                                                    <?= $fetch_order['product_name'] ?>
                                                </span>
                                            </div>
                                            <div class='col-md-8 text-md-center'>
                                                <div class='row'>
                                                    <div class='col-sm-3'>
                                                        <span class='d-inline d-md-none'>
                                                            รวม
                                                        </span>
                                                        <span class='d-inline d-md-none'>
                                                            :
                                                        </span>
                                                        <span class='d-inline d-md-block'>
                                                            ฿<?= $fetch_order['price_per_item'] ?>
                                                        </span>
                                                    </div>
                                                    <div class='col-sm-3'>
                                                        <span class='d-inline d-md-none'>
                                                            จำนวน
                                                        </span>
                                                        <span class='d-inline d-md-none'>
                                                            :
                                                        </span>
                                                        <span class='d-inline d-md-block'>
                                                            <?= $fetch_order['quantity'] ?>
                                                        </span>
                                                    </div>
                                                    <div class='col-sm-3'>
                                                        <span class='d-inline d-md-none'>
                                                            รวม
                                                        </span>
                                                        <span class='d-inline d-md-none'>
                                                            :
                                                        </span>
                                                        <span class='d-inline d-md-block'>
                                                            ฿<?= $fetch_order['total_price'] ?>
                                                        </span>
                                                    </div>
                                                    <div class='col-sm-3'>
                                                        <?php
                                                        switch ($fetch_order['status']) {
                                                            case 'processing':
                                                                if (empty($fetch_order['track_code'])) {
                                                                    echo 'รอผู้ขายทำการจัดส่ง';
                                                                } else {
                                                                    echo 'กำลังทำการจัดส่ง';
                                                                }
                                                                break;
                                                            case 'shipped':
                                                                echo 'จัดส่งสำเร็จ';
                                                                break;
                                                            case 'cancelled':
                                                                echo 'ยกเลิกการสั่งซื้อ';
                                                                break;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $current_loop += 1;
                                if ($current_loop == $loop) {
                                ?>
                                    <div class='alert-secondary col py-1'>
                                        <div class='d-flex justify-content-end text-md-right'>
                                            <span>
                                                ค่าจัดส่ง : <?= $fetch_order['shipping_price'] ?>
                                            </span>
                                            <div style='width: 30px'></div>
                                            <span>
                                                ยอดคำสั่งซื้อทั้งหมด :
                                                &nbsp;
                                            </span>
                                            <h4 class='text-orange'>
                                                ฿ <?= $fetch_order['total_product_price'] ?>
                                            </h4>
                                        </div>
                                        <div class='d-flex justify-content-end align-items-center text-center text-md-left'>
                                            <?php
                                            if ($fetch_order['status'] == 'processing' && empty($fetch_order['track_code'])) {
                                            ?>
                                                <div class='btn btn-success' onclick='window.location.href = "?page=store_order&order_id=<?= $fetch_order['order_id'] ?>"'>จัดการออเดอร์</div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class='btn btn-primary' onclick='window.location.href = "?page=store_order&order_id=<?= $fetch_order['order_id'] ?>"'>รายละเอียดออเดอร์</div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
<?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }
