<style>
    .product-name-home {
        font-size: 1.1rem;
    }

    .product-price-home {
        font-size: 1rem;
    }

    .product-sale-home {
        font-size: 1rem;
    }

    .img-categories-list {
        width: 4em;
        background-color: #f1f1f1;
    }

    .product-categories-text {
        font-size: 14px;
        margin-top: 5px;
    }

    .product-categories-box {
        border-radius: 0px 0px 0px 0px;
        -moz-border-radius: 0px 0px 0px 0px;
        -webkit-border-radius: 0px 0px 0px 0px;
        border: .5px solid rgba(0, 0, 0, .05);
        padding: 10px 3px 5px 3px;
        height: 9rem;
        align-items: center !important;
    }

    .product-categories-col {
        margin-top: 0 !important;
        padding: 0;
    }

    .row-fix {
        margin: 0 !important;
        margin-bottom: 5px !important;
    }
</style>
<script>
    updateItemInCart();
</script>
<div class="search-box-header card">
    <div class="d-flex justify-content-start align-items-center bd-highlight">
        <div class="p-2 bd-highlight" style="width: 90%"><input type="text" class="w-100 form-control" name="search" id="search" placeholder="พิมพ์เพื่อค้นหา"></div>
        <div class="p-2 bd-highlight">
            <div class="btn btn-green" onclick="window.location.href = '?page=search&query=' + $('#search').val()">ค้นหา</div>
        </div>
    </div>
</div>
<br>
<div class="section-banner-header card">
    <div class="row">
        <div class="col-sm-8">
            <div id="carouselBannerControls" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselBannerControls" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselBannerControls" data-slide-to="1"></li>
                    <li data-target="#carouselBannerControls" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <?php
                    try {
                        $sql_banner = 'SELECT banner_img, banner_alt FROM banner WHERE banner_tier = "%banner_tier" AND start_time < NOW() AND end_time > NOW()';
                        $res_banner = mysqli_query($connect, str_replace('%banner_tier', '0', $sql_banner));
                        if ($res_banner) {
                            $banner_active = true;
                            while ($fetch_banner = mysqli_fetch_assoc($res_banner)) {
                    ?>
                                <div class="carousel-item <?php if ($banner_active) {
                                                                echo 'active';
                                                                $banner_active = false;
                                                            } ?>">
                                    <img class="d-block w-100" src="<?php echo $fetch_banner['banner_img']; ?>" alt="<?php echo $fetch_banner['banner_alt']; ?>">
                                </div>
                    <?php
                            }
                            $banner_active = true;
                        } else {
                            echo $sql_banner;
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselBannerControls" role="button" data-slide="prev">
                    <i class="far fa-chevron-left"></i>
                </a>
                <a class="carousel-control-next" href="#carouselBannerControls" role="button" data-slide="next">
                    <i class="far fa-chevron-right"></i>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="carouselSubBannerControls1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    try {
                        $res_banner = mysqli_query($connect, str_replace('%banner_tier', '1', $sql_banner));
                        if ($res_banner) {
                            $banner_active = true;
                            while ($fetch_banner = mysqli_fetch_assoc($res_banner)) {
                    ?>
                                <div class="carousel-item <?php if ($banner_active) {
                                                                echo 'active';
                                                                $banner_active = false;
                                                            } ?>">
                                    <img class="d-block w-100" src="<?php echo $fetch_banner['banner_img']; ?>" alt="<?php echo $fetch_banner['banner_alt']; ?>">
                                </div>
                    <?php
                            }
                            $banner_active = true;
                        } else {
                            echo $sql_banner;
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    ?>
                </div>
            </div>
            <div id="carouselSubBannerControls2" class="carousel slide" data-ride="carousel" style="margin-top: .6em;">
                <div class="carousel-inner">
                    <?php
                    try {
                        $res_banner = mysqli_query($connect, str_replace('%banner_tier', '2', $sql_banner));
                        if ($res_banner) {
                            $banner_active = true;
                            while ($fetch_banner = mysqli_fetch_assoc($res_banner)) {
                    ?>
                                <div class="carousel-item <?php if ($banner_active) {
                                                                echo 'active';
                                                                $banner_active = false;
                                                            } ?>">
                                    <img class="d-block w-100" src="<?php echo $fetch_banner['banner_img']; ?>" alt="<?php echo $fetch_banner['banner_alt']; ?>">
                                </div>
                    <?php
                            }
                            $banner_active = true;
                        } else {
                            echo $sql_banner;
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<!--
<div class="product-categories">
    <div class="card">
        <div style="margin-top: 10px; padding-right: 15px;">
            <div class="p-2 bd-highlight">หมวดหมู่</div>
        </div>
        <div class="row row-fix text-center">
            <?php
            try {
                $sql_category = 'SELECT * FROM category';
                $res_category = mysqli_query($connect, $sql_category);
                while ($fetch_category = mysqli_fetch_assoc($res_category)) {
            ?>
                    <div class='col-sm-1-2 m-0 p-0'>
                        <div class="product-categories-box">
                            <img class="rounded-circle img-categories-list" src="<?php echo $fetch_category['category_url']; ?>" alt="<?php echo $fetch_category['category_alt']; ?>">
                            <div class="product-categories-text"><?php echo $fetch_category['category_name']; ?></div>
                        </div>
                    </div>
            <?php
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
    </div>
</div>
<br>
-->
<!--
<div class="top-product">
    <div class="card">
        <div class="d-flex justify-content-between" style="margin-top: 10px; padding-right: 15px;">
            <div class="p-2 bd-highlight">ขายดีประจำสัปดาห์</div>
            <div class="p-2 bd-highlight"><a class='text-dark' href='?page=best_seller'>ดูเพิ่มเติม <i class="far fa-angle-double-right"></i></a></div>
        </div>
        <div class="row row-fix">
            <div class="col-sm-2-5">
                <img loading="lazy" class="card-img-top" src="https://cf.shopee.co.th/file/5606b1695389ed56c7d8684730631301" alt="Card image cap">
            </div>
            <div class="col-sm-2-5">
                <img loading="lazy" class="card-img-top" src="https://cf.shopee.co.th/file/5606b1695389ed56c7d8684730631301" alt="Card image cap">
            </div>
            <div class="col-sm-2-5">
                <img loading="lazy" class="card-img-top" src="https://cf.shopee.co.th/file/5606b1695389ed56c7d8684730631301" alt="Card image cap">
            </div>
            <div class="col-sm-2-5">
                <img loading="lazy" class="card-img-top" src="https://cf.shopee.co.th/file/5606b1695389ed56c7d8684730631301" alt="Card image cap">
            </div>
            <div class="col-sm-2-5">
                <img loading="lazy" class="card-img-top" src="https://cf.shopee.co.th/file/5606b1695389ed56c7d8684730631301" alt="Card image cap">
            </div>
        </div>
    </div>
</div>
<br>
-->
<div class="categories">
    <div class="card">
        <div style="margin-top: 10px; padding: 0px 15px 0px 15px;">
            <div class="p-2 bd-highlight">สินค้าแนะนำ</div>
        </div>
        <!--
        <div class="row d-flex justify-content-center text-center">
            <div class="col-sm-2 d-none d-lg-block d-print-block">
                <div class="align-self-center">
                    <div class="categories-line"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div>
                    <b>Categories</b>
                </div>
            </div>
            <div class="col-sm-2 d-none d-lg-block d-print-block">
                <div class="align-self-center">
                    <div class="categories-line"></div>
                </div>
            </div>
        </div>
        -->
        <div class="row row-fix">
            <?php
            $sql_product = 'SELECT product.product_id, product.product_name, product.product_price, product.store_id, IF(ISNULL(SUM(sub_order.quantity)), 0, SUM(sub_order.quantity)) AS product_order_quantity, product_img.img_url FROM product LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id LEFT JOIN sub_order ON product.product_id = sub_order.product_id WHERE product.store_id != 0 GROUP BY product.product_id ORDER BY product_order_quantity DESC';
            $res_product = mysqli_query($connect, $sql_product);
            while ($fetch_product = mysqli_fetch_assoc($res_product)) {
            ?>
                <div class="col-sm-4 col-md-3 col-lg-2 col-top">
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
            <?php } ?>
        </div>
    </div>
</div>