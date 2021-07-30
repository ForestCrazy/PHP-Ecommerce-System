<?php
$sql_product = 'SELECT * FROM product INNER JOIN store ON product.store_id = store.store_id WHERE product.product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '"';
$res_product = mysqli_query($connect, $sql_product);
if (!$res_product) {
    gotoPage('home');
} else {
    if (mysqli_num_rows($res_product) == 1) {
        $fetch_product = mysqli_fetch_assoc($res_product);
?>
        <style>
            .carousel-indicators li {
                background-color: #000;
            }

            .rating-container .caption {
                display: none !important;
            }

            .rating-md {
                font-size: 0px !important;
            }
        </style>
        <div class="row">
            <div class="col-lg-4 col-top">
                <?php
                $sql_product_asset = 'SELECT img_url FROM product_img WHERE product_id = "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '" ORDER BY weight ASC';
                $res_product_asset = mysqli_query($connect, $sql_product_asset);
                if ($res_product_asset) {
                ?>
                    <div id="carouselBannerControls" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php
                            $product_asset_active = true;
                            for ($i = 0; $i < mysqli_num_rows($res_product_asset); $i++) {
                            ?>
                                <li data-target="#carouselBannerControls" data-slide-to="<?php echo $i; ?>" <?php if ($product_asset_active) {
                                                                                                                echo 'class="active"';
                                                                                                                $product_asset_active = false;
                                                                                                            } ?>></li>
                            <?php
                            }
                            $product_asset_active = true;
                            ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php
                            while ($fetch_product_asset = mysqli_fetch_assoc($res_product_asset)) {
                            ?>
                                <div class="carousel-item <?php if ($product_asset_active) {
                                                                echo 'active';
                                                                $product_asset_active = false;
                                                            } ?>">
                                    <img class="d-block w-100" src="<?php echo $fetch_product_asset['img_url']; ?>">
                                </div>
                            <?php
                            }
                            $product_asset_active = true;
                            ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselBannerControls" role="button" data-slide="prev">
                            <i class="far fa-chevron-left carousel-control-prev-icon text-dark"></i>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselBannerControls" role="button" data-slide="next">
                            <i class="far fa-chevron-right carousel-control-next-icon text-dark"></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php
                } else {
                    echo $sql_product_asset;
                }
                ?>
            </div>
            <div class="col-lg-8 col-top">
                <div class='product_info card w-100 h-100' style='padding: 1rem; max-height: 350px;'>
                    <p class="lead font-weight-bold"><?php echo $fetch_product['product_name']; ?></p>
                    <!--
        <a><span class="badge purple mr-1">Category 2</span></a>
        <a><span class="badge blue mr-1">New</span></a>
        <a><span class="badge red mr-1">Bestseller</span></a>
        -->
                    <section class="mdb-color lighten-5 p-1">
                        <?php
                        if (is_int($fetch_product['special_price'])) {
                        ?>
                            <del>฿ <?php echo $fetch_product['product_price']; ?></del>
                        <?php
                        } else {
                        ?>
                            <h4 style='margin-bottom: 0!important;'>฿ <?php echo $fetch_product['product_price']; ?></h4>
                        <?php
                        }
                        ?>
                        <?php
                        if (is_int($fetch_product['special_price'])) {
                        ?>
                            <h4 style='margin-bottom: 0!important;'>฿ <?php echo $fetch_product['product_price']; ?></h4>
                        <?php
                        }
                        ?>
                    </section>
                    <div class="row">
                        <div class="col-3 col-top"><label class="shipping-label">การจัดส่ง</label></div>
                        <div class="col-9 col-top">
                            <div class="shipping-type">
                                <!--<img src="https://image.flaticon.com/icons/svg/3176/3176178.svg" width="25px" height="25px">--><i class="fas fa-truck"></i> ฟรีค่าจัดส่ง
                            </div>
                        </div>
                    </div>
                    <form name="add-product-to-cart" action="javascript:void(0)">
                        <!-- Default input -->
                        <div class="row">
                            <div class="col-3"><label class="shipping-label">จำนวน</label></div>
                            <div class="col-3">
                                <input type="number" value="1" aria-label="Search" min="1" max="<?php echo $fetch_product['product_quantity']; ?>" class="form-control w-100">
                            </div>
                            <div class="col-6">มีสินค้าทั้งหมด <?php echo $fetch_product['product_quantity']; ?> ชิ้น</div>
                        </div>
                        <br>
                        <button class="btn btn-primary waves-effect waves-light">เพิ่มเข้าตะกร้า
                            <i class="fas fa-shopping-cart ml-1"></i>
                        </button>
                        <a href='?page=checkout&p_id=<?php echo $_GET['p_id']; ?>'>
                            <div class="btn btn-success waves-effect waves-light">ซื้อสินค้า</div>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="store-info-section card col-top" style="padding: 10px 10px 10px 10px;">
            <div class="row">
                <!--<div class="col-sm-6 d-flex justify-content-start align-items-center" style="margin: 1em;">
            <div style="width: 90px;">
                <img class="rounded-circle z-depth-2 img-fluid" src="https://cf.shopee.co.th/file/acfb710a76fee2c2794fa5f22a858ed9_tn">
            </div>
            <!--<div class="row">
                <div class="col-6">
                    Store Name<br>
                    <small>Active 30 นาทีที่ผ่านมา</small>
                </div>
                <div class="col-6">
                    <button class="btn">
                        ดูร้านค้า
                    </button>
                </div>
            </div>-->
                <!--<div style="margin-left: 15px; width: 160px;">
                Store Name<br>
                <small>ใช้งานล่าสุดเมื่อ 30 นาทีที่ผ่านมา</small>
            </div>
            <div>
                <button class="btn btn-link"><i class="fas fa-store"></i>&nbsp; ดูร้านค้า</button>
                <button class="btn btn-link"><i class="far fa-comments"></i>&nbsp; แชทกับร้าน</button>
            </div>
        </div>
        <div class="col-sm-6">
            
        </div>-->
                <!--
        <div class="col-sm-6 top-buffer d-flex justify-content-start">
            <div class="row" style="width: 100%; align-items: center!important;">
                <div class="col-4">
                    <div>
                        <img class="z-depth-2 img-fluid" style="width: 150px;" src="https://cf.shopee.co.th/file/acfb710a76fee2c2794fa5f22a858ed9_tn">
                    </div>
                </div>
                <div class="col-8">
                    Store Name<br>
                    <small>
                        Active 30 นาทีที่ผ่านมา<br>
                        <i class="far fa-map-marker-alt"></i> จังหวัดสงขลา
                    </small>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="store-info-label">
                <div class="d-flex justify-content-around bd-highlight mb-3">
                    <div class="p-2 bd-highlight store-info-sublabel">คะแนน&nbsp;2.3 พัน</div>
                    <div class="p-2 bd-highlight store-info-sublabel">สินค้า&nbsp;157 ชิ้น</div>
                </div>
                <div class="d-flex justify-content-around bd-highlight mb-3">
                    <div class="p-2 bd-highlight store-info-sublabel">ตอบกลับ&nbsp;147 ครั้ง</div>
                    <div class="p-2 bd-highlight store-info-sublabel">ผู้ติดตาม&nbsp;2.3 พัน</div>
                </div>
            </div>
        </div>
        -->
                <div class='col-sm-6' style='margin-top: 0!important;'>
                    <div class='row'>
                        <div class='col-4 col-sm-3 col-md-4 col-lg-3 col-xl-3' style='margin-top: 0!important;'>
                            <img class="z-depth-2 img-fluid" style="width: 150px;" src="https://cf.shopee.co.th/file/acfb710a76fee2c2794fa5f22a858ed9_tn">
                        </div>
                        <div class='col-8 col-sm-9 col-md-8 col-lg-9 col-xl-9' style='margin-top: 0!important;'>
                            <h5 clas='align-middle'><?php echo $fetch_product['store_name']; ?></h5>
                            <!--<br>-->
                            <small>
                                <!--
                                    Active 30 นาทีที่ผ่านมา<br>
                                -->
                                <i class="far fa-map-marker-alt"></i> จังหวัด <?= $fetch_product['store_province'] ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php
                $sql_store_score = 'SELECT COUNT(product_review.review_id) AS store_review_amount FROM store INNER JOIN product ON store.store_id = product.store_id INNER JOIN sub_order ON product.product_id = sub_order.product_id INNER JOIN product_review ON sub_order.sub_order_id = product_review.sub_order_id WHERE store.store_id = "' . $fetch_product['store_id'] . '"';
                $res_store_score = mysqli_query($connect, $sql_store_score);
                $sql_store_product_amount = 'SELECT COUNT(product.product_id) AS store_product_amount FROM store INNER JOIN product ON store.store_id = product.store_id WHERE store.store_id = "' . $fetch_product['store_id'] . '"';
                $res_store_product_amount = mysqli_query($connect, $sql_store_product_amount);
                $sql_store_subscriber = 'SELECT COUNT(user_id) AS store_follower_amount FROM store_follower WHERE store_id = "' . $fetch_product['store_id'] . '"';
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
                                เปิดร้านเมื่อ&nbsp;<?= date('d-m-Y', strtotime($fetch_product['createtime'])) ?>
                            </div>
                            <div class='col-6 bd-highlight p-2' style='margin-top: 0!important;'>
                                ผู้ติดตาม&nbsp;<?= number_abbr($fetch_store_subscriber['store_follower_amount']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 card col-top" style="padding: 1rem;">
            <b>รายละเอียดสินค้า</b>
            <!--
        <b>Description Product</b>
    -->
            <div class="categories-line"></div>
            <div class="description-product">
                <?php echo $fetch_product['product_description']; ?>
            </div>
        </div>
        <?php
        $sql_product_review = 'SELECT AVG(product_review.star_score) AS star_score FROM product INNER JOIN sub_order ON product.product_id = sub_order.order_id INNER JOIN product_review ON sub_order.sub_order_id = product_review.sub_order_id WHERE product.product_id = "' . $fetch_product['product_id'] . '"';
        $res_product_review = mysqli_query($connect, $sql_product_review);
        if (!$res_product_review) {
            gotoPage('home');
        } else {
            $fetch_product_review = mysqli_fetch_assoc($res_product_review);
        }
        ?>
        <div class="col-sm-12 card col-top" style="padding: 1rem;">
            <b>คะแนนของสินค้า</b>
            <div class="categories-line"></div>
            <div class="score-product card default-color col-top">
                <div class='col d-flex justify-content-start'>
                    <div class='score-product-container col-top'>
                        <input id="product-review" class="rating" data-stars="5" data-step="0.1" title="" value='<?= $fetch_product_review['star_score'] ?>' data-show-clear='false' readonly />
                    </div>
                    <div class='score-product-text text-white col-top'>
                        &emsp;<h3 style='display: inline;'><?= round($fetch_product_review['star_score'], 1) ?></h3>
                        <div style='display: inline;'>เต็ม 5</div>
                    </div>
                </div>
            </div>
            <?php
            $sql_product_review = 'SELECT user.username, product_review.star_score, product_review.review_msg FROM user INNER JOIN `order` AS order_product ON user.u_id = order_product.u_id INNER JOIN sub_order ON order_product.order_id = sub_order.order_id INNER JOIN product_review ON sub_order.sub_order_id = product_review.sub_order_id WHERE sub_order.product_id = "' . $fetch_product['product_id'] . '"';
            $res_product_review = mysqli_query($connect, $sql_product_review);
            if (!$res_product_review) {
                gotoPage('home');
            } else {
                while ($fetch_product_review = mysqli_fetch_assoc($res_product_review)) {
            ?>
                    <div class='col col-top'>
                        <small><?= substr_replace($fetch_product_review['username'], '***', -3) ?></small><br />
                        <input class="rating" data-stars="5" data-step="0.1" title="" value='<?= $fetch_product_review['star_score'] ?>' data-show-clear='false' data-size='xs' style='display: none !important;' readonly />
                        <?= $fetch_product_review['review_msg'] ?>
                        <div class="categories-line"></div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    <?php
    } else {
    ?>
        <script>
            window.location.href = '?page=home';
        </script>
<?php
    }
}
?>