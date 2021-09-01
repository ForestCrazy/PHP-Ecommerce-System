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
        $(document).ready(function() {
            $("input[type=checkbox]").change(function() {
                updateOrderDetail();
            });
        });
        updateItemInCart();
    </script>
    <div class='row col-top'>
        <h3>ตระกร้าสินค้าของฉัน</h3>
        <div class='col-12'>
            <?php
            $sql_cart = 'SELECT store.store_id, store.store_name FROM cart INNER JOIN product ON cart.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id WHERE cart.u_id = "' . $_SESSION['u_id'] . '" GROUP BY store.store_id';
            $res_cart = mysqli_query($connect, $sql_cart);
            if ($res_cart) {
                while ($fetch_cart_store = mysqli_fetch_assoc($res_cart)) {
            ?>
                    <div id='store-<?= $fetch_cart_store['store_id'] ?>' class='card col-top'>
                        <div class='store-info' style='padding-left: 1rem; padding-right: 1rem; margin-top: 0.8rem;'>
                            <i class="far fa-store"></i> <?= $fetch_cart_store['store_name'] ?>
                        </div>
                        <?php
                        $sql_cart_product = 'SELECT product.*, cart.*, product_img.img_url FROM product INNER JOIN cart ON product.product_id = cart.product_id INNER JOIN store ON product.store_id = store.store_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE store.store_id = "' . $fetch_cart_store['store_id'] . '" AND cart.u_id = "' . $_SESSION['u_id'] . '"';
                        $res_cart_product = mysqli_query($connect, $sql_cart_product);
                        if ($res_cart_product) {
                            while ($fetch_cart_product = mysqli_fetch_assoc($res_cart_product)) {
                        ?>
                                <div id="product-<?= $fetch_cart_product['product_id'] ?>" data-storeId="<?= $fetch_cart_store['store_id'] ?>" class="col card col-top">
                                    <div class="h-100 d-flex justify-content-start align-items-center">
                                        <input type="checkbox" class="productSelect" name="product_checkout[]" value="<?= $fetch_cart_product['product_id'] ?>">
                                        <img src="<?= $fetch_cart_product['img_url'] ?>" style="max-height: 120px;" alt="" class="img-fluid">
                                        <div class="flex-fill">
                                            <div class="row" style="margin-left: 0px!important; margin-right: 0px!important;">
                                                <div class="col-10">
                                                    <h5 id="product-name-<?= $fetch_cart_product['product_id'] ?>" class="card-title"><?= $fetch_cart_product['product_name'] ?></h5>
                                                    <div class="row text-md-center">
                                                        <div class="col-sm-4">
                                                            <span class="d-inline d-md-block">
                                                                ราคาต่อชิ้น
                                                            </span>
                                                            <span class="d-inline d-md-none"> :</span>
                                                            <span id="product-price-<?= $fetch_cart_product['product_id'] ?>" class="d-inline d-md-block">
                                                                <?= $fetch_cart_product['product_price'] ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-8 col-sm-4">
                                                            <span class="d-inline d-md-block">จำนวนสินค้า</span>
                                                            <span class="d-inline d-md-none"> : </span>
                                                            <div class="d-flex justify-content-md-center">
                                                                <div class="def-number-input number-input safari_only d-flex justify-content-center">
                                                                    <button onclick="changeItemQuantity('<?= $fetch_cart_product['product_id'] ?>', '-')" class="minus"></button>
                                                                    <input class="quantity" min="0" id="product-qty-<?= $fetch_cart_product['product_id'] ?>" name="quantity" value="<?= $fetch_cart_product['quantity'] ?>" type="number">
                                                                    <button onclick="changeItemQuantity('<?= $fetch_cart_product['product_id'] ?>', '+')" class="plus"></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-8 col-sm-4">
                                                            <span class="d-inline d-md-block">
                                                                ราคารวม
                                                            </span>
                                                            <span class="d-inline d-md-none">
                                                                :
                                                            </span>
                                                            <span id="all-price-product-<?= $fetch_cart_product['product_id'] ?>" class="d-inline d-md-block">
                                                                <?= $fetch_cart_product['product_price'] * $fetch_cart_product['quantity'] ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="float-right">
                                                        <button class="btn btn-danger d-none d-sm-block waves-effect waves-light" onclick="removeItemFromCart(<?= $fetch_cart_product['product_id'] ?>)">
                                                            ลบ
                                                        </button>
                                                        <span class="d-block d-sm-none remove-btn-text" onclick="removeItemFromCart(<?= $fetch_cart_product['product_id'] ?>)">
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
                    <div class='btn btn-danger' onclick='removeSelectItemFromCart()'>
                        ลบสินค้าที่เลือก
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='btn btn-primary' style='background-color: rgb(255, 69, 0);' onclick='addFavoriteSelectItemFromCart()'>
                        ย้ายไปยังสินค้าที่ถูกใจ
                    </div>
                </div>
                <div class='col-md-3'>
                    <div>
                        <span class='d-inline'>จำนวนสินค้าที่เลือก : </span>
                        <h5 id='count-product-select' class='d-inline' style='color: var(--red);'>0</h5>
                    </div>
                    <div>
                        <span class='d-inline'>รวม : </span>
                        <h5 id='all-product-price' class='d-inline' style='color: var(--red);'>฿0</h5>
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
