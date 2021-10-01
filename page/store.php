<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    if (isset($_GET['store_id'])) {
        $sql_store = 'SELECT * FROM store WHERE store_id = "' . $_GET['store_id'] . '"';
        $res_store = mysqli_query($connect, $sql_store);
        if ($res_store) {
            $fetch_store = mysqli_fetch_assoc($res_store);
?>
            <div class='col col-top'>
                <div class="store-info-section card col-top p-2">
                    <div class="row">
                        <div class='col-sm-6' style='margin-top: 0!important;'>
                            <div class='row'>
                                <div class='col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3' style='margin-top: 0!important;'>
                                    <img class="z-depth-2 img-fluid store-profile" src="<?= !empty($fetch_store['store_img']) ? $fetch_store['store_img'] : '/asset/img/store_profile/store_default.png' ?>">
                                </div>
                                <div class='col-8 col-sm-9 col-md-8 col-lg-9 col-xl-9' style='margin-top: 0!important;'>
                                    <h5 clas='align-middle'><?php echo $fetch_store['store_name']; ?></h5>
                                    <!--<br>-->
                                    <small>
                                        <!--
                                    Active 30 นาทีที่ผ่านมา<br>
                                -->
                                        <i class="far fa-map-marker-alt"></i> จังหวัด <?= $fetch_store['store_province'] ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php
                        $sql_store_score = 'SELECT COUNT(product_review.review_id) AS store_review_amount FROM store INNER JOIN product ON store.store_id = product.store_id INNER JOIN sub_order ON product.product_id = sub_order.product_id INNER JOIN product_review ON sub_order.sub_order_id = product_review.sub_order_id WHERE store.store_id = "' . $fetch_store['store_id'] . '"';
                        $res_store_score = mysqli_query($connect, $sql_store_score);
                        $sql_store_product_amount = 'SELECT COUNT(product.product_id) AS store_product_amount FROM store INNER JOIN product ON store.store_id = product.store_id WHERE store.store_id = "' . $fetch_store['store_id'] . '"';
                        $res_store_product_amount = mysqli_query($connect, $sql_store_product_amount);
                        $sql_store_subscriber = 'SELECT COUNT(user_id) AS store_follower_amount FROM store_follower WHERE store_id = "' . $fetch_store['store_id'] . '"';
                        $res_store_subscriber = mysqli_query($connect, $sql_store_subscriber);
                        if (!$res_store_score || !$res_store_product_amount || !$res_store_subscriber) {
                            gotoPage('home');
                        } else {
                            $fetch_store_score = mysqli_fetch_assoc($res_store_score);
                            $fetch_store_product_amount = mysqli_fetch_assoc($res_store_product_amount);
                            $fetch_store_subscriber = mysqli_fetch_assoc($res_store_subscriber);
                        }
                        ?>
                        <div class='col-sm-6 d-flex align-items-center' style='margin-top: 0!important;'>
                            <div class='store-info-label text-center col-12' style='margin-top: 0!important;'>
                                <div class='row'>
                                    <div class='col-6 bd-highlight p-2' style='margin-top: 0!important;'>
                                        คะแนน&nbsp;<?= number_abbr($fetch_store_score['store_review_amount']) ?>
                                    </div>
                                    <div class='col-6 bd-highlight p-2' style='margin-top: 0!important;'>
                                        สินค้า&nbsp;<?= number_abbr($fetch_store_product_amount['store_product_amount']) ?> ชิ้น
                                    </div>
                                    <div class='col-6 bd-highlight p-2' style='margin-top: 0!important;'>
                                        เปิดร้านเมื่อ&nbsp;<?= date('d-m-Y', strtotime($fetch_store['createtime'])) ?>
                                    </div>
                                    <div class='col-6 bd-highlight p-2' style='margin-top: 0!important;'>
                                        ผู้ติดตาม&nbsp;<?= number_abbr($fetch_store_subscriber['store_follower_amount']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col col-top'>
                สินค้าในร้าน
                <div class='card p-2'>
                    <div class="row row-fix">
                        <?php
                        $sql_product = 'SELECT product.product_id, product.product_name, product.product_price, product.store_id, IF(ISNULL(SUM(sub_order.quantity)), 0, SUM(sub_order.quantity)) AS product_order_quantity, product_img.img_url FROM product LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id LEFT JOIN sub_order ON product.product_id = sub_order.product_id WHERE product.store_id = "' . $fetch_store['store_id'] . '" GROUP BY product.product_id ORDER BY product_order_quantity DESC';
                        $res_product = mysqli_query($connect, $sql_product);
                        if ($res_product) {
                            while ($fetch_product = mysqli_fetch_assoc($res_product)) {
                        ?>
                                <div class="col-sm-4 col-md-3 col-lg-3 col-xl-2 col-top">
                                    <div class="card">
                                        <a class='text-dark' href='?page=product&p_id=<?php echo $fetch_product['product_id']; ?>'>
                                            <img loading="lazy" class="card-img-top w-100" src="<?php echo $fetch_product['img_url']; ?>" alt="<?php echo $fetch_product['product_name']; ?>">
                                            <div style="padding: 5px 6px 0px 6px;">
                                                <p class="card-text product-name-home"><?php echo $fetch_product['product_name']; ?></p>
                                                <div class="d-flex justify-content-between bd-highlight mb-3">
                                                    <div class="bd-highlight product-price-home">฿ <?php echo $fetch_product['product_price']; ?></div>
                                                    <div class="bd-highlight"></div>
                                                    <div class="bd-highlight product-sale-home"><small>ขายแล้ว <?php echo $fetch_product['product_order_quantity']; ?> ชิ้น</small></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        gotoPage('home');
    }
}
