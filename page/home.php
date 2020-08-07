<style>
    .categories-line {
        display: inline-block;
        background-color: #aaa;
        vertical-align: middle;
        width: 100%;
        height: 1px;
        content: "";
    }

    .product-name-home {
        font-size: 1.1rem;
    }

    .product-price-home {
        font-size: 1rem;
    }

    .product-sale-home {
        font-size: 1rem;
    }
</style>
<div class="search-box-header card">
    <div class="d-flex justify-content-start align-items-center bd-highlight">
        <div class="p-2 bd-highlight" style="width: 90%"><input type="text" class="w-100 form-control" name="search" placeholder="พิมพ์เพื่อค้นหา"></div>
        <div class="p-2 bd-highlight"> <button type="submit" class="btn btn-green">ค้นหา</button></div>
    </div>
</div>
<br>
<div class="section-banner-header card">
    <div class="row top-buffer">
        <div class="col-sm-8">
            <div id="carouselBannerControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="asset/img/banner1.png" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner2.png" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner3.png" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselBannerControls" role="button" data-slide="prev">
                    <i class="far fa-chevron-left carousel-control-prev-icon"></i>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselBannerControls" role="button" data-slide="next">
                    <i class="far fa-chevron-right carousel-control-next-icon"></i>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div id="carouselSubBannerControls1" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="asset/img/banner2.png" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner1.png" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner3.png" alt="Third slide">
                    </div>
                </div>
            </div>
            <div id="carouselSubBannerControls2" class="carousel slide" data-ride="carousel" style="margin-top: .6em;">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="asset/img/banner3.png" alt="Third slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner2.png" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="asset/img/banner1.png" alt="First slide">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="top-product">
    <div class="card">
        <div class="d-flex justify-content-between" style="margin-top: 10px; padding-right: 15px;">
            <div class="p-2 bd-highlight">ขายดีประจำสัปดาห์</div>
            <div class="p-2 bd-highlight">ดูเพิ่มเติม <i class="far fa-angle-double-right"></i></div>
        </div>
        <div class="row" style="padding: 0px 5px 5px 5px;">
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
<div class="categories">
    <div class="card">
        <div style="margin-top: 10px; padding: 0px 15px 0px 15px;">
            <div class="p-2 bd-highlight">สินค้าแนะนำ</div>
        </div>

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
        <div style="padding: 0px 5px 5px 5px;">
            <div class="row">
                <?php $i = 0;
                while ($i < 40) { $i++;?>
                    <div class="col-sm-2-5">
                        <div class="card">
                            <img loading="lazy" class="card-img-top w-100" src="https://cf.shopee.co.th/file/7a81d50c5304f71ca9ff9f6824ca55ba" alt="Card image cap">
                            <div style="padding: 5px 6px 0px 6px;">
                                <p class="card-text product-name-home">Product Name</p>
                                <div class="d-flex justify-content-between bd-highlight mb-3">
                                    <div class="bd-highlight product-price-home">฿ Price</div>
                                    <div class="bd-highlight"></div>
                                    <div class="bd-highlight product-sale-home"><small>ขายแล้ว ชิ้น</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>