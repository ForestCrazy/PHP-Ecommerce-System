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

        function checkoutFromCart() {

            var productSelect = $('input#productSelect:checked').map(function() {
                return $(this).val();
            })
            productSelect = productSelect.get();
            if (productSelect.length > 0) {
                $.get('API/generateCartTemp.php', {
                    p_id: productSelect
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
                            <input type='checkbox' id='productSelect' name='product_checkout[]' value='<?= $fetch_cart['product_id'] ?>' />
                            <img src='<?= $fetch_cart['img_url'] ?>' style="max-height: 120px;" alt="" class="img-fluid" />
                            <div class='flex-fill'>
                                <div class='row' style='margin-left: 0px!important; margin-right: 0px!important;'>
                                    <div class='col-10'>
                                        <h5 class='card-title'><?= $fetch_cart['product_name'] ?></h5>
                                        ราคาต่อชิ้น : <?= $fetch_cart['product_price'] ?>
                                        <br />
                                        จำนวนสินค้า : <?= $fetch_cart['quantity'] ?>
                                        <br />
                                        ราคารวม : <?= $fetch_cart['product_price'] * $fetch_cart['quantity'] ?>
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
            <input type='checkbox' id='selectAllItem' /> เลือกสินค้าทั้งหมด
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
