<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $product_price = 0;

?>
    <script>
        var url = new URL(window.location.href);
        var p_idCache = url.searchParams.get('p_id') ? url.searchParams.get('p_id') : null;
        var changeShippingProviderWithUrls = null;
        $(document).ready(function() {
            $.get('API/lastShippingAddr.php').then((res) => {
                var resp = JSON.parse(res);
                $('input[type="radio"][value="' + resp['lastShippingAddr'] + '"]').attr('checked', true);
            })
        })

        function changeShippingProvider(p_id, currentShippingId, withUrls = false) {
            p_idCache = p_id;
            changeShippingProviderWithUrls = withUrls;
            $('#ShippingProviderModal').modal('show');
            $.get('/API/productShippingProvider.php', {
                p_id: p_id
            }).then((res) => {
                var resp = JSON.parse(res);
                if (resp.success) {
                    $('#ShippingProviderModal').find('#productShippingProviderList').html('');
                    resp.data.map((item) => {
                        const isCurrentShippingProvider = item.shipping_provider_id == currentShippingId ? 'checked' : null;
                        $('#ShippingProviderModal').find('#productShippingProviderList').append('<div class="custom-control"><input type="radio" name="shipping_id" class="shipping_id" value="' + item.shipping_provider_id + '" ' + isCurrentShippingProvider + '> <span class="font-weight-bold">' + item.shipping_name + '</span> เวลาในการจัดส่ง <small style="font-weight: 800;">' + item.shipping_time + '</small> วันค่าจัดส่ง <small style="font-weight: 800">' + item.shipping_price + '</small> บาท</div>');
                    });
                }
            })
            $('#ShippingProviderModal').on('shown.bs.modal', function() {});
            $('#ShippingProviderModal').on('hidden.bs.modal', function() {});
        }

        function updateShippingProvider(p_id) {
            var shipping_id = $('input[name="shipping_id"]:checked').val();
            if (changeShippingProviderWithUrls) {
                window.location.href = '?page=checkout&p_id=' + url.searchParams.get('p_id') + '&p_quantity=' + url.searchParams.get('p_quantity') + '&shipping_id=' + shipping_id;
            } else {
                $.get('/API/updateItemCartShippingProvider.php', {
                    shipping_id: shipping_id,
                    p_id: p_id ? p_id : p_idCache
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        $('#ShippingProviderModal').find('#productShippingProviderList').html('');
                        location.reload();
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to change shipping provider');
                    }
                })
            }
        }

        function checkout() {
            var shipping_addr = $('input[type="radio"][name="shipping_addr"]:checked').val();
            var payment_method = $('input[type="radio"][name="payment"]:checked').val();
            if (url.searchParams.get('p_id')) {
                var p_id = url.searchParams.get('p_id');
                var p_quantity = url.searchParams.get('p_quantity');
                var shipping_id = url.searchParams.get('shipping_id');
                $.get('/API/checkoutWithProductId.php', {
                    p_id: p_id,
                    p_quantity: p_quantity,
                    p_qty: p_quantity,
                    shipping_addr: shipping_addr,
                    shipping_id: shipping_id,
                    payment_method: payment_method
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        console.log('create order success');
                        window.location.href = '?page=order_result&order_id=' + resp.orderId;
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to create sub order');
                    }
                })
            } else {
                $.post('/API/checkoutWithCartId.php', {
                    cart_id: url.searchParams.get('cart_id'),
                    shipping_addr: shipping_addr,
                    payment_method: payment_method
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        console.log('checkout with cart_id success');
                        location.href = '?page=order_result&cart_id=' + url.searchParams.get('cart_id');
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to checkout with cart_id');
                    }
                })
            }
        }
    </script>
    <style>
        .btn.active {
            border: 1px solid var(--success);
        }
    </style>
    <div class="modal fade" id="ShippingProviderModal" tabindex="-1" role="dialog" aria-labelledby="ShippingProviderModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ShippingProviderModalLabel">ตัวเลือกการจัดส่ง</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id='productShippingProviderList'>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="updateShippingProvider()">บันทึก</button>
                </div>
            </div>
        </div>
    </div>
    <div class='card col-top' style='padding: 1rem;'>
        <div class='col-12'>
            ที่อยู่ในการจัดส่ง
        </div>
        <?php
        $sql_shipping_addr = 'SELECT * FROM address WHERE u_id = "' . $_SESSION['u_id'] . '"';
        $res_shipping_addr = mysqli_query($connect, $sql_shipping_addr);
        if ($res_shipping_addr) {
            if (mysqli_num_rows($res_shipping_addr) == 0) {
        ?>
                <div class='col text-right'>
                    <button class='btn btn-outline-success' type="button" data-toggle="collapse" data-target="#collapse-address" aria-expanded="false" aria-controls="collapse-address">เพิ่มที่อยู่</button>
                    <div class='collapse text-left' id='collapse-address'>
                        <div class='row'>
                            <div class='col-md-4 form-group'>
                                <label>ชื่อ</label>
                                <input type='text' class='form-control' name='first-name' id='first-name' />
                            </div>
                            <div class='col-md-4 form-group'>
                                <label>นามสกุล</label>
                                <input type='text' class='form-control' name='last-name' id='last-name' />
                            </div>
                            <div class='col-md-4 form-group'>
                                <label>เบอร์โทร</label>
                                <input type='text' class='form-control' name='phone' id='phone' />
                            </div>
                            <div class='col-md-9 form-group'>
                                <label>ที่อยู่</label>
                                <input type='text' class='form-control' name='address' id='address' />
                            </div>
                            <div class='col-md-3 form-group'>
                                <label>อำเภอ</label>
                                <input type='text' class='form-control' name='city' id='city' />
                            </div>
                            <div class='col-md-6 form-group'>
                                <label>จังหวัด</label>
                                <input type='text' class='form-control' name='province' id='province' />
                            </div>
                            <div class='col-md-6 form-group'>
                                <label>รหัสไปรษณีย์</label>
                                <input type='text' class='form-control' name='zip-code' id='zip-code' />
                            </div>
                            <div class='col btn btn-success' onclick="createAddress()">
                                บันทึก
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            while ($fetch_shipping_addr = mysqli_fetch_assoc($res_shipping_addr)) {
            ?>
                <label>
                    <input type='radio' name='shipping_addr' id='shipping-addr' value='<?= $fetch_shipping_addr['address_id'] ?>' /> <?= $fetch_shipping_addr['first_name'] . ' ' . $fetch_shipping_addr['last_name'] . ' ' . $fetch_shipping_addr['phone'] . '&emsp;' . $fetch_shipping_addr['address'] . ', อำเภอ' . $fetch_shipping_addr['city'] . ', จังหวัด' . $fetch_shipping_addr['province'] . ', ' . $fetch_shipping_addr['zip_code'] ?>
                </label>
        <?php
            }
        }
        ?>
    </div>
    <?php
    $total_product_price = 0;
    $total_shipping_price = 0;
    if (isset($_GET['p_id'])) {
        if (!isset($_GET['p_quantity'])) {
            $_GET['p_quantity'] = 1;
        }
        $sql_product = 'SELECT product.product_id, product.product_name, product.product_price, store.store_name, product_img.img_url FROM product INNER JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id INNER JOIN store ON product.store_id = store.store_id WHERE product.product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
        $res_product = mysqli_query($connect, $sql_product);
        if ($res_product) {
            $fetch_product = mysqli_fetch_assoc($res_product);
            $total_product_price += $fetch_product['product_price'] * $_GET['p_quantity'];
    ?>
            <div class='card col-top pt-3'>
                <div class='d-flex justify-content-start align-items-center'>
                    <div class='text-nowrap' style='width: 120px;'>
                        &emsp;
                        <i class="far fa-store"></i>
                        <?= $fetch_product['store_name'] ?>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='d-flex justify-content-start align-items-center'>
                    <img src='<?= $fetch_product['img_url'] ?>' style='max-height: 120px; max-width: 120px;' class='img-fluid' />
                    <div class='flex-fill'>
                        <div class='row'>
                            <div class='col-md-4'>
                                <span>
                                    <?= $fetch_product['product_name'] ?>
                                </span>
                            </div>
                            <div class='col-md-8 text-md-center'>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <span class='d-inline d-md-none'>
                                            ราคาต่อชิ้น
                                        </span>
                                        <span class='d-inline d-md-none'>
                                            :
                                        </span>
                                        <span class='d-inline d-md-block'>
                                            ฿<?= $fetch_product['product_price'] ?>
                                        </span>
                                    </div>
                                    <div class='col-sm-4'>
                                        <span class='d-inline d-md-none'>
                                            จำนวน
                                        </span>
                                        <span class='d-inline d-md-none'>
                                            :
                                        </span>
                                        <span class='d-inline d-md-block'>
                                            <?= isset($_GET['p_quantity']) ? $_GET['p_quantity'] : '1' ?>
                                        </span>
                                    </div>
                                    <div class='col-sm-4'>
                                        <span class='d-inline d-md-none'>
                                            รวม
                                        </span>
                                        <span class='d-inline d-md-none'>
                                            :
                                        </span>
                                        <span class='d-inline d-md-block'>
                                            ฿<?= isset($_GET['p_quantity']) ? $_GET['p_quantity'] * $fetch_product['product_price'] : $fetch_product['product_price'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='alert-secondary p-1'>
                    <div class='d-flex justify-content-around align-items-center text-center text-md-left'>
                        <span class='d-inline'>
                            ช่องทางการจัดส่ง
                        </span>
                        <span class='d-inline'>
                            <?php
                            $sql_check_shipping = 'SELECT shipping_provider.shipping_provider_id, shipping_provider.shipping_name, product_shipping.shipping_price FROM product_shipping INNER JOIN shipping_provider ON product_shipping.shipping_provider_id = shipping_provider.shipping_provider_id WHERE product_shipping.product_id = "' . $_GET['p_id'] . '" AND shipping_provider.shipping_provider_id = "' . $_GET['shipping_id'] . '"';
                            $res_check_shipping = mysqli_query($connect, $sql_check_shipping);
                            if ($res_check_shipping) {
                                if (mysqli_num_rows($res_check_shipping) == 1) {
                                    $fetch_shipping = mysqli_fetch_assoc($res_check_shipping);
                                    echo $fetch_shipping['shipping_name'];
                                } else {
                                    $sql_shipping_provider = 'SELECT * FROM product_shipping WHERE product_id = "' . $_GET['p_id'] . '" ORDER BY shipping_price ASC LIMIT 1';
                                    $res_shipping_provider = mysqli_query($connect, $sql_shipping_provider);
                                    if ($res_check_shipping) {
                                        $fetch_shipping = mysqli_fetch_assoc($res_shipping_provider);
                                    }
                                }
                                $total_shipping_price += $fetch_shipping['shipping_price'];
                            } else {
                                gotoPage('502');
                            }
                            ?>
                        </span>
                        <span class='text-primary' onclick="changeShippingProvider(<?= $_GET['p_id'] ?>, <?= $fetch_shipping['shipping_provider_id'] ?>, true)">
                            เปลี่ยน
                        </span>
                        <span class='d-sm-block d-inline'>
                            ฿<?= $fetch_shipping['shipping_price'] ?>
                            <input type='hidden' name='shipping_price' value='<?= $fetch_shipping['shipping_price'] ?>' />
                        </span>
                    </div>
                </div>
            </div>
            <?php
        }
    } elseif (isset($_GET['cart_id'])) {
        $sql_store = 'SELECT store.store_id, store.store_name FROM cart_temp INNER JOIN product ON cart_temp.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart_temp.u_id GROUP BY store.store_id';
        $res_store = mysqli_query($connect, $sql_store);
        if ($res_store) {
            while ($fetch_store = mysqli_fetch_assoc($res_store)) {
            ?>
                <div class='card col-top pt-3'>
                    <div class='d-flex justify-content-start align-items-center'>
                        <div class='text-nowrap' style='width: 120px;'>
                            &emsp;
                            <i class="far fa-store"></i>
                            <?= $fetch_store['store_name'] ?>
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
                                            การจัดส่ง
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $sql_shipping_store = 'SELECT cart.shipping_id FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" GROUP BY cart.shipping_id';
                    $res_shipping_store = mysqli_query($connect, $sql_shipping_store);
                    if ($res_shipping_store) {
                        while ($fetch_shipping_store = mysqli_fetch_assoc($res_shipping_store)) {
                            $sql_product_checkout = 'SELECT product.*, cart.*, shipping_provider.shipping_name, product_img.img_url FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN shipping_provider ON cart.shipping_id = shipping_provider.shipping_provider_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                            $res_product_checkout = mysqli_query($connect, $sql_product_checkout);
                            if ($res_product_checkout) {
                                while ($fetch_product_checkout = mysqli_fetch_assoc($res_product_checkout)) {
                                    $total_product_price += $fetch_product_checkout['product_price'] * $fetch_product_checkout['quantity'];
                    ?>
                                    <div class='d-flex justify-content-start align-items-center'>
                                        <img src='<?= $fetch_product_checkout['img_url'] ?>' style='max-height: 120px; max-width: 120px;' class='img-fluid' />
                                        <div class='flex-fill'>
                                            <div class='row'>
                                                <div class='col-md-4'>
                                                    <span>
                                                        <?= $fetch_product_checkout['product_name'] ?>
                                                    </span>
                                                </div>
                                                <div class='col-md-8 text-md-center'>
                                                    <div class='row'>
                                                        <div class='col-sm-3'>
                                                            <span class='d-inline d-md-none'>
                                                                ราคาต่อชิ้น
                                                            </span>
                                                            <span class='d-inline d-md-none'>
                                                                :
                                                            </span>
                                                            <span class='d-inline d-md-block'>
                                                                ฿<?= $fetch_product_checkout['product_price'] ?>
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
                                                                <?= $fetch_product_checkout['quantity'] ?>
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
                                                                ฿<?= $fetch_product_checkout['product_price'] * $fetch_product_checkout['quantity'] ?>
                                                            </span>
                                                        </div>
                                                        <div class='col-sm-3'>
                                                            <span class='d-inline d-md-none'>
                                                                การจัดส่ง
                                                            </span>
                                                            <span class='d-inline d-md-none'>
                                                                :
                                                            </span>
                                                            <span class='d-inline d-md-block'>
                                                                <?= $fetch_product_checkout['shipping_name'] ?>
                                                                <span class='text-primary' onclick="changeShippingProvider(<?= $fetch_product_checkout['product_id'] ?>, <?= $fetch_product_checkout['shipping_id'] ?>, false)">เปลี่ยน</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            $sql_shipping = 'SELECT product.store_id, cart.shipping_id, shipping_provider.shipping_name, MAX(product_shipping.shipping_price) AS shipping_price FROM cart_temp INNER JOIN cart ON cart_temp.product_id = cart.product_id INNER JOIN product ON cart.product_id = product.product_id INNER JOIN product_shipping ON product.product_id = product_shipping.product_id AND cart.shipping_id = product_shipping.shipping_provider_id INNER JOIN shipping_provider ON product_shipping.shipping_provider_id = shipping_provider.shipping_provider_id WHERE cart_temp.cart_id = "' . $_GET['cart_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '" AND product.store_id = "' . $fetch_store['store_id'] . '" AND cart.shipping_id = "' . $fetch_shipping_store['shipping_id'] . '"';
                            $res_shipping = mysqli_query($connect, $sql_shipping);
                            if ($res_shipping) {
                                $fetch_shipping = mysqli_fetch_assoc($res_shipping);
                                $total_shipping_price += $fetch_shipping['shipping_price'];
                            } else {
                                gotoPage('502');
                            }
                            ?>
                            <div class='alert-secondary p-1'>
                                <div class='d-flex justify-content-around align-items-center text-center text-md-left'>
                                    <span class='d-inline'>
                                        ช่องทางการจัดส่ง
                                    </span>
                                    <span class='d-inline'>
                                        <?= $fetch_shipping['shipping_name'] ?>
                                    </span>
                                    <span class='d-sm-block d-inline'>
                                        ฿<?= $fetch_shipping['shipping_price'] ?>
                                    </span>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
    <?php
            }
        }
    } else {
        gotoPage('home');
    }
    ?>
    <div class='card col-top pt-3'>
        <div class='align-middle col'>
            <div class='d-inline'>
                ช่องทางการชำระเงิน
            </div>
            <div class='btn-group btn-group-toggle d-inline' data-toggle='buttons'>
                <label class='btn btn-primary form-check-label active'>
                    <input class='form-check-input' type='radio' name='payment' value='cod' checked />
                    เก็บเงินปลายทาง
                </label>
                <label class='btn btn-primary form-check-label'>
                    <input class='form-check-input' type='radio' name='payment' value='transfer' />
                    โอนเงินผ่านบัญชีธนาคาร
                </label>
            </div>
        </div>
        <hr />
        <div class='row'>
            <div class='col-md-6'></div>
            <div class='col-md-6'>
                <div class='row col pr-0'>
                    <div class='text-left col-6'>
                        ยอดรวมสินค้า:
                    </div>
                    <div class='text-right col-6'>
                        ฿ <?= $total_product_price ?>
                    </div>
                </div>
                <div class='row col pr-0'>
                    <div class='text-left col-6'>
                        ยอดรวมค่าจัดส่ง:
                    </div>
                    <div class='text-right col-6'>
                        ฿ <?= $total_shipping_price ?>
                    </div>
                </div>
                <div class='row col pr-0'>
                    <div class='text-left col-6'>
                        รวมยอดที่ต้องชำระ:
                    </div>
                    <div class='text-right col-6 font-weight-bold'>
                        ฿ <?= $total_product_price + $total_shipping_price ?>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class='text-right p-2'>
            <div class='btn btn-success' onclick="checkout()">
                สั่งซื้อสินค้า
            </div>
        </div>
    </div>
<?php
}
