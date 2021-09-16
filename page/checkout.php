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
        updateItemInCart();

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
                $.get('/updateItemCartShippingProvider.php', {
                    shipping_provider_id: shipping_id,
                    p_id: p_id ? p_id : p_idCache
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        $('#ShippingProviderModal').find('#productShippingProviderList').htm('');
                        location.reload();
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to change shipping provider');
                    }
                })
            }
        }

        function checkout() {
            if (url.searchParams.get('p_id')) {
                var p_id = url.searchParams.get('p_id');
                var p_quantity = url.searchParams.get('p_quantity');
                var shipping_id = url.searchParams.get('shipping_id');
                var shipping_price = $('input[type="hidden"][name="shipping_price"]').val();
                var order_id = createOrder(shipping_id, shipping_price);
                order_id.then((orderId) => {
                    $.get('/API/createSubOrderWithProductId.php', {
                        p_id: p_id,
                        p_quantity: p_quantity,
                        p_qty: p_quantity,
                        order_id: orderId
                    }).then((res) => {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            console.log('create sub order success');
                            window.location.href = '?page=order_result&order_id=' + orderId;
                        } else {
                            console.error(resp.reason ? resp.reason : 'failed to create sub order');
                        }
                    })
                })
            }
        }

        function createOrder(shipping_id, shipping_price) {
            return new Promise((resolve, reject) => {
                var shipping_addr = $('input[type="radio"][name="shipping_addr"]:checked').val();
                var payment_method = $('input[type="radio"][name="payment"]:checked').val();
                $.get('/API/createOrder.php', {
                    shipping_addr: shipping_addr,
                    shipping_id: shipping_id,
                    payment_method: payment_method,
                    shipping_price: shipping_price
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        console.log('create order success. order id : ' + resp.orderId);
                        resolve(resp.orderId);
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to create order');
                    }
                })
            })
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
    <div class='card col-top pt-3'>
        <div class='d-flex justify-content-start align-items-center'>
            <div class='text-nowrap' style='width: 120px;'>
                &emsp;
                <i class="far fa-store"></i>
                ForestCrazy_Dev
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
        <div class='d-flex justify-content-start align-items-center'>
            <img src='/asset/img/product_img/1631278166-ตี๋เล็กซาลาเปา-เปาหมูแดง.jpg' style='max-height: 120px;' class='img-fluid' />
            <div class='flex-fill'>
                <div class='row'>
                    <div class='col-md-4'>
                        <span>
                            ซาลาเปาหมูแดง
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
                                    ฿100
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
                                    0
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
                                    ฿100
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
                                    Kerry Express
                                    <span class='text-primary'>เปลี่ยน</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='d-flex justify-content-start align-items-center'>
            <img src='/asset/img/product_img/1631278166-ตี๋เล็กซาลาเปา-เปาหมูแดง.jpg' style='max-height: 120px;' class='img-fluid' />
            <div class='flex-fill'>
                <div class='row'>
                    <div class='col-md-4'>
                        <span>
                            ซาลาเปาหมูแดง
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
                                    ฿100
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
                                    0
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
                                    ฿100
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
                                    Kerry Express
                                    <span class='text-primary'>เปลี่ยน</span>
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
                    Kerry Express
                </span>
                <span class='d-sm-block d-inline'>
                    ฿0
                </span>
            </div>
        </div>
    </div>
    <?php
    if (isset($_GET['p_id'])) {
        $sql_product = 'SELECT product.product_id, product.product_name, product.product_price, store.store_name, product_img.img_url FROM product INNER JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id INNER JOIN store ON product.store_id = store.store_id WHERE product.product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
        $res_product = mysqli_query($connect, $sql_product);
        if ($res_product) {
            $fetch_product = mysqli_fetch_assoc($res_product);
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
                    <img src='/asset/img/product_img/1631278166-ตี๋เล็กซาลาเปา-เปาหมูแดง.jpg' style='max-height: 120px;' class='img-fluid' />
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
                    <input class='form-check-input' type='radio' name='payment' value='tranfer' />
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
                        300
                    </div>
                </div>
                <div class='row col pr-0'>
                    <div class='text-left col-6'>
                        ยอดรวมค่าจัดส่ง:
                    </div>
                    <div class='text-right col-6'>
                        30
                    </div>
                </div>
                <div class='row col pr-0'>
                    <div class='text-left col-6'>
                        รวมยอดที่ต้องชำระ:
                    </div>
                    <div class='text-right col-6'>
                        330
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
