<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce System</title>
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="asset/css/mdb.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="asset/fontawesome/css/all.css">
    <script src="asset/js/jquery.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.14.0/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f7f7f7;
        }

        .section-banner-header {
            background-color: white;
            border-radius: 8px 8px 8px 8px;
            padding: 1.0rem !important;
        }

        [class*="col-"] {
            margin-top: 15px;
        }

        .sup-shopping-cart {
            top: -.5em;
            position: relative;
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline;
        }

        .shipping-label {
            width: 110px;
        }

        .top-buffer {
            margin-top: -15px;
        }
    </style>
</head>

<body>
    <!--Navbar -->
    <nav class="mb-1 navbar navbar-expand-lg navbar-dark default-color">
        <a class="navbar-brand" href="?page=home">Ecommerce</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333" aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" id="home">
                    <a class="nav-link" href="?page=home"><i class="far fa-home"></i> หน้าหลัก</a>
                </li>
                <li class="nav-item" id="sell">
                    <a class="nav-link" href="?page=sell"><i class="far fa-store-alt"></i> ขายสินค้า</a>
                </li>
                <li class="nav-item" id="event">
                    <a class="nav-link" href="?page=event"><i class="far fa-calendar-star"></i> กิจกรรม</a>
                </li>
                <li class="nav-item" id="auction">
                    <a class="nav-link" href="?page=auction"><i class="far fa-gavel"></i> การประมูล</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light">
                        <i class="far fa-shopping-cart"></i>
                        <sup class="sup-shopping-cart"><span class="badge badge-success">23</span></sup> ตะกร้าสินค้า

                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light">
                        <i class="far fa-bell"></i> การแจ้งเตือน
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> บัญชีของฉัน
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
                        <a class="dropdown-item" href="#"><i class="far fa-sliders-v-square"></i> จัดการบัญชีของฉัน</a>
                        <a class="dropdown-item" href="#"><i class="far fa-box"></i> รายการสั่งซื้อของฉัน</a>
                        <a class="dropdown-item" href="#"><i class="far fa-heart"></i> รายการสินค้าที่ฉันชอบ</a>
                        <a class="dropdown-item" href="#"><i class="far fa-star"></i> รายการร้านค้าที่ฉันชอบ</a>
                        <a class="dropdown-item" href="#"><i class="far fa-comment"></i> ประวัติการแสดงความคิดเห็น</a>
                        <a class="dropdown-item" href="#"><i class="far fa-sign-out"></i> ออกจากระบบ</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!--/.Navbar -->
    <div class="container">
        <?php if (!$_GET) {
            $_GET["page"] = 'home';
        }
        if (!$_GET["page"]) {
            $_GET["page"] = "home";
        }
        if ($_GET["page"] == "home") {
            include_once __DIR__ . '/page/home.php';
        } elseif ($_GET['page'] == "product") {
            include_once __DIR__ . '/page/product.php';
        } elseif ($_GET['page'] == "event") {
            include_once __DIR__ . '/page/event.php';
        } elseif ($_GET['page'] == "cart") {
            include_once __DIR__ . '/page/cart.php';
        } elseif ($_GET['page'] == "login") {
            include_once __DIR__ . '/page/login.php';
        } elseif ($_GET['page'] == "logout") {
            include_once __DIR__ . '/page/logout.php';
        } else {
            echo '<br>';
            echo '<div class="container"><div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> ไม่พบหน้าที่ท่านร้องขอ กำลังพาท่านกลับไปหน้าหลัก...</div></div>';
            echo '<meta http-equiv="refresh" content="3;URL=?page=home"';
        } ?>
    </div>
    <br>
    <script defer src="asset/js/bootstrap.js"></script>
    <script defer src="asset/js/mdb.js"></script>
    <script defer src="asset/js/popper.js"></script>
</body>

<!-- Footer -->
<footer class="page-footer font-small teal pt-4">
    <div class="container-fluid text-md-left">
        <div class="row">
            <div class="col-sm-3">
                <b>ศูนย์ช่วยเหลือ</b>
                <ul>
                    <li>Live Chat</li>
                    <li>คำถามที่พบบ่อย</li>
                    <li>สั่งซื้อสินค้าอย่างไร</li>
                    <li>เริ่มขายสินค้าอย่างไร</li>
                    <li>ช่องทางการชำระเงิน</li>
                    <li>การจัดส่งสินค้า</li>
                    <li>การคืนเงินและคืนสินค้า</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <b>เกี่ยวกับเรา</b>
                <ul>
                    <li>เกี่ยวกับเรา</li>
                    <li>ร่วมงานกับเรา</li>
                    <li>นโยบายความเป็นส่วนตัว</li>
                    <li>Seller Center</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <b>หัวข้อยอดนิยม</b>
                <ul>
                    <li>บลา</li>
                    <li>บลา</li>
                    <li>บลา</li>
                    <li>บลา</li>
                    <li>บลา</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <div class="text-align">
                    <b>ติดตามพวกเรา</b>
                    <ul>
                        <li>fb</li>
                        <li>line</li>
                        <li>mail</li>
                        <li>google+</li>
                        <li>youtube</li>
                        <li>instagram</li>
                        <li>twitter</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright text-center py-3" style="background-color:#2f3133!important">
        <small style="font-size:14px; color: white;">Copyright © <?php echo date("Y"); ?> TPS Marketplace</small>
    </div>
    </div>
</footer>

</html>