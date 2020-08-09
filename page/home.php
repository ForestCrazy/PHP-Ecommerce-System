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
        height: 8rem;
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
                <ol class="carousel-indicators">
                    <li data-target="#carouselBannerControls" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselBannerControls" data-slide-to="1"></li>
                    <li data-target="#carouselBannerControls" data-slide-to="2"></li>
                </ol>
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
<div class="product-categories">
    <div class="card">
        <div style="margin-top: 10px; padding-right: 15px;">
            <div class="p-2 bd-highlight">หมวดหมู่</div>
        </div>
        <div class="row row-fix text-center">
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/tshirt.png">
                    <div class="product-categories-text">เสื้อผ้าแฟชั่นผู้ชาย</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/tshirt.png">
                    <div class="product-categories-text">เสื้อผ้าแฟชั่นผู้หญิง</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">คอมพิวเตอร์และแล็ปท็อป</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เครื่องใช้ไฟฟ้าภายในบ้าน</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">อาหารเสริมและผลิตภัณฑ์สุขภาพ</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">ความงามและของใช้ส่วนตัว</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">รองเท้าผู้ชาย</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">รองเท้าผู้หญิง</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">นาฬิกาและแว่นตา</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">กระเป๋า</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">กล้องและอุปกรณ์ถ่ายภาพ</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เครื่องประดับ</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">ยานยนต์</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">อาหารและเครื่องดื่ม</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">สื่อบันเทิงภายในบ้าน</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เครื่องใช้ในบ้าน</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เกมและอุปกรณ์เสริม</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">ของเล่น สินค้าแม่และเด็ก</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">กีฬาและกิจกรรมกลางแจ้ง</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เครื่องเขียน หนังสือ และดนตรี</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">อุปกรณ์วงจรไฟฟ้าและอะไหล่</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">สัตว์เลี้ยง</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">ตั๋วและบัตรกำนัน</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">มือถือและอุปกรณ์เสริม</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">บ้านและที่ดิน</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">เซรั่มและทรีทเมนต์</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">ดิน ปุ๋ย และอุปกรณ์เพาะชำ</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">หูฟังเอียบัด</div>
                </div>
            </div>
            <div class="col-sm-1-2 product-categories-col">
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">อุปกรณ์ติดตั้งบนมอเตอร์ไซค์</div>
                </div>
                <div class="product-categories-box">
                    <img class="rounded-circle img-categories-list" src="asset/img/laptop.png">
                    <div class="product-categories-text">อื่นๆ</div>
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
        <div class="row row-fix">
            <?php $i = 0;
            while ($i < 40) {
                $i++; ?>
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