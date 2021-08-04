<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <style>
        .remove-btn-text:hover {
            color: var(--red) !important;
            cursor: pointer;
        }

        .number-input input[type="number"] {
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .number-input input[type=number]::-webkit-inner-spin-button,
        .number-input input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .number-input {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .number-input button {
            -webkit-appearance: none;
            background-color: transparent;
            border: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: 0;
            position: relative;
        }

        .number-input button:before,
        .number-input button:after {
            display: inline-block;
            position: absolute;
            content: '';
            height: 2px;
            transform: translate(-50%, -50%);
        }

        .number-input button.plus:after {
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .number-input input[type=number] {
            text-align: center;
        }

        .number-input.number-input {
            border: 1px solid #ced4da;
            width: 10rem;
            border-radius: .25rem;
        }

        .number-input.number-input button {
            width: 5rem;
            height: .7rem;
        }

        .number-input.number-input button.minus {
            padding-left: 10px;
        }

        .number-input.number-input button:before,
        .number-input.number-input button:after {
            width: .7rem;
            background-color: #495057;
        }

        .number-input.number-input input[type=number] {
            max-width: 4rem;
            padding: .5rem;
            border: 1px solid #ced4da;
            border-width: 0 1px;
            font-size: 1rem;
            height: 2rem;
            color: #495057;
        }

        @media not all and (min-resolution:.001dpcm) {
            @supports (-webkit-appearance: none) and (stroke-color:transparent) {

                .number-input.def-number-input.safari_only button:before,
                .number-input.def-number-input.safari_only button:after {
                    margin-top: -.3rem;
                }
            }
        }
    </style>
    <script>
        function removeItemFromCart(p_id) {
            $.get('API/removeItemFromCart.php', {
                p_id: p_id
            }, function(res) {
                var resp = JSON.parse(res);
                if (resp['success'] == true) {
                    Swal.fire(
                        'ลบสินค้าออกจากตระกร้าสำเร็จ',
                        '',
                        'success',
                    ).then(() => {
                        window.location.reload();
                    })
                } else {
                    Swal.fire(
                        'เกิดข้อผิดพลาดในการลบสินค้าออกจากตระกร้า',
                        '',
                        'error',
                    ).then(() => {
                        window.location.reload();
                    })
                }
            })
        }

        function checkoutFromCart(p_id = 0) {

            var productSelect = $('input.productSelect:checked').map(function() {
                return $(this).val();
            })
            productSelect = p_id != 0 ? p_id : productSelect.get();
            if (productSelect.length > 0) {
                $.post('API/generateCartTemp.php', {
                    p_id: productSelect,
                    format: p_id !== 0 ? 'number' : 'array'
                }).then((res) => {
                    try {
                        var resp = JSON.parse(res);
                    } catch (e) {
                        Swal.fire(
                            'เกิดข้อผิดพลาดในการสั่งซื้อสินค้า',
                            'เกิดข้อผิดพลาดไม่ทราบสาเหตุ',
                            'error',
                        )
                    }
                    if (resp['success'] == true) {
                        window.location.href = '?page=checkout&cart_id=' + resp['cart_token'];
                    } else {
                        Swal.fire(
                            'เกิดข้อผิดพลาดในการสั่งซื้อสินค้า',
                            resp['reason'] ? resp['reason'] : '',
                            'error',
                        )
                    }
                })
            } else {
                Swal.fire(
                    'เกิดข้อผิดพลาดในการสั่งซื้อสินค้า',
                    'กรุณาเลือกสินค้าก่อนทำการสั่งซื้อ',
                    'error',
                )
            }

        }

        function selectAllItem(checked) {
            if (checked) {
                $('input.productSelect').not(this).prop('checked', true);
            } else {
                $('input.productSelect').not(this).prop('checked', false);
            }
        }

        function changeItemQuantity(p_id, operator, min_val, max_val) {
            var cur_val, def_val = parseInt(document.getElementById('product-qty-' + p_id).value);
            if (cur_val == 1 && operator == '-') {
                removeItemFromCart(p_id);
            } else {
                if (cur_val > 0) {
                    if (operator == '+') {
                        if (cur_val < max_val) {
                            cur_val += 1;
                            document.getElementById('product-qty-' + p_id).value = cur_val;
                        }
                    } else {
                        cur_val -= 1;
                        document.getElementById('product-qty-' + p_id).value = cur_val;
                    }
                }
            }
            $.get('API/updateItemCartQty.php', {
                p_id: p_id,
                qty: document.getElementById('product-qty-' + p_id).value
            }).then((res) => {
                var resp = JSON.parse(res);
                if (resp['success'] !== true) {
                    document.getElementById('product-qty-' + p_id).value = def_val;
                }
            })
        }
    </script>
    <div class='row col-top'>
        <h3>ตระกร้าสินค้าของฉัน</h3>
        <div class='col-12'>
            <?php
            $sql_cart = 'SELECT * FROM cart INNER JOIN product ON cart.product_id = product.product_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id INNER JOIN product_shipping ON product.product_id = product_shipping.product_id AND cart.shipping_id = product_shipping.shipping_provider_id WHERE cart.u_id = "' . $_SESSION['u_id'] . '"';
            $res_cart = mysqli_query($connect, $sql_cart);
            if ($res_cart) {
                while ($fetch_cart = mysqli_fetch_assoc($res_cart)) {
            ?>
                    <div class='col card col-top'>
                        <div class="h-100 d-flex justify-content-start align-items-center">
                            <input type='checkbox' class='productSelect' name='product_checkout[]' value='<?= $fetch_cart['product_id'] ?>' />
                            <img src='<?= $fetch_cart['img_url'] ?>' style="max-height: 120px;" alt="" class="img-fluid" />
                            <div class='flex-fill'>
                                <div class='row' style='margin-left: 0px!important; margin-right: 0px!important;'>
                                    <div class='col-10'>
                                        <h5 class='card-title'><?= $fetch_cart['product_name'] ?></h5>
                                        <div class='row text-md-center'>
                                            <div class='col-sm-4'>
                                                <span class='d-inline d-md-block'>
                                                    ราคาต่อชิ้น
                                                </span>
                                                <span class='d-inline d-md-none'> :</span>
                                                <span class='d-inline d-md-block'>
                                                    <?= $fetch_cart['product_price'] ?>
                                                </span>
                                            </div>
                                            <div class='col-8 col-sm-4'>
                                                <span class='d-inline d-md-block'>จำนวนสินค้า</span>
                                                <span class='d-inline d-md-none'> : </span>
                                                <div class='d-flex justify-content-md-center'>
                                                    <div class="def-number-input number-input safari_only d-flex justify-content-center">
                                                        <button onclick="changeItemQuantity('<?= $fetch_cart['product_id'] ?>', '-', <?= $fetch_cart['product_quantity'] ?>)" class="minus"></button>
                                                        <input class="quantity" min="0" id='product-qty-<?= $fetch_cart['product_id'] ?>' name="quantity" value="<?= $fetch_cart['quantity'] ?>" type="number">
                                                        <button onclick="changeItemQuantity('<?= $fetch_cart['product_id'] ?>', '+', <?= $fetch_cart['product_quantity'] ?>)" class="plus"></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-8 col-sm-4'>
                                                <span class='d-inline d-md-block'>
                                                    ราคารวม
                                                </span>
                                                <span class='d-inline d-md-none'>
                                                    :
                                                </span>
                                                <span class='d-inline d-md-block'>
                                                    <?= $fetch_cart['product_price'] * $fetch_cart['quantity'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='float-right'>
                                            <button class='btn btn-danger d-none d-sm-block' onclick='removeItemFromCart(<?= $fetch_cart['product_id'] ?>)'>
                                                ลบ
                                            </button>
                                            <span class='d-block d-sm-none remove-btn-text' onclick='removeItemFromCart(<?= $fetch_cart['product_id'] ?>)'>
                                                ลบ
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
            ?>
        </div>
    </div>
    <div class='result-cart card col-top p-1' style='position: -webkit-sticky; position: sticky; bottom:0;'>
        <div class='col-12'>
            <h3>รายละเอียดการสั่งซื้อ</h3>
            <div class="categories-line"></div>
            <input type='checkbox' id='selectAllItem' onclick='selectAllItem(this.checked)' /> เลือกสินค้าทั้งหมด
            <div class="categories-line"></div>
            <div class='row text-center'>
                <div class='col-md-3'>
                    <button class='btn btn-danger'>
                        ลบสินค้าที่เลือก
                    </button>
                </div>
                <div class='col-md-3'>
                    <button class='btn btn-primary' style='background-color: rgb(255, 69, 0);'>
                        ย้ายไปยังสินค้าที่ถูกใจ
                    </button>
                </div>
                <div class='col-md-3'>
                    <div>
                        <span class='d-inline'>จำนวนสินค้าที่เลือก : </span>
                        <h5 class='d-inline' style='color: var(--red);'>0</h5>
                    </div>
                    <div>
                        <span class='d-inline'>รวม : </span>
                        <h5 class='d-inline' style='color: var(--red);'>฿0</h5>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='btn text-white' style='background-color: #ffac44;' onclick='checkoutFromCart()'>
                        สั่งซื้อสินค้า
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
