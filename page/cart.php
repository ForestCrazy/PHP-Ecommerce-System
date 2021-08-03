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
                            <input type='checkbox' name='product_id' value='<?= $fetch_cart['product_id'] ?>'/>
                            <img src='<?= $fetch_cart['img_url'] ?>' style="max-height: 120px;" alt="" class="img-fluid" />
                            <div class='flex-fill'>
                                <div class='row' style='margin-left: 0px!important; margin-right: 0px!important;'>
                                    <div class='col-10'>
                                        <h5 class='card-title'><?= $fetch_cart['product_name'] ?></h5>
                                        ราคาต่อชิ้น : <?= $fetch_cart['product_price'] ?>
                                        <br/>
                                        จำนวนสินค้า : <?= $fetch_cart['quantity'] ?>
                                        <br/>
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
<?php
}
