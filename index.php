<?php
require('./system/database.php');
require('./system/oop.php');

if (!$_GET) {
    $_GET["page"] = 'home';
}
if (!isset($_GET['page'])) {
    $_GET['page'] = 'home';
}
if (!$_GET["page"]) {
    $_GET["page"] = "home";
}

if (isset($_SESSION['username'])) {
    $user_info = array();
    $sql_check_hasStore = 'SELECT store_name FROM store WHERE u_id = "' . $_SESSION['u_id'] . '"';
    $res_check_store = mysqli_query($connect, $sql_check_hasStore);
    if ($res_check_store) {
        if (mysqli_num_rows($res_check_store) == 1) {
            $user_info['hasStore'] = true;
        } else {
            $user_info['hasStore'] = false;
        }
    }
    $user_info['itemInCart'] = 0;
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce System</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="asset/fontawesome/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" type="text/css" rel="stylesheet">
    <!-- Sweet Alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart JS -->
    <script src='https://cdn.jsdelivr.net/npm/chart.js@3.4.1/dist/chart.min.js'></script>
    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Main.JS -->
    <script type='text/javascript' src='asset/js/main.js'></script>
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

        .col-top {
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

        .categories-line {
            display: inline-block;
            background-color: #aaa;
            vertical-align: middle;
            width: 100%;
            height: 1px;
            content: "";
        }
    </style>
    <script>
        updateItemInCart();
    </script>
</head>

<body>
    <?php
    if ($_GET['page'] == 'store_dashboard' || $_GET['page'] == 'store_order' || $_GET['page'] == 'store_product' || $_GET['page'] == 'store_setting') {
    ?>
        <!--Navbar -->
        <nav class="mb-1 navbar navbar-expand-lg navbar-dark default-color">
            <a class="navbar-brand" href="?page=store_dashboard">Store Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333" aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item" id="home">
                        <a class="nav-link" href="?page=home"><i class="far fa-home"></i> หน้าหลัก</a>
                    </li>
                    <li class="nav-item" id="sell">
                        <a class="nav-link" href="?page=store_dashboard"><i class="fas fa-chart-line"></i></i> แดชบอร์ด</a>
                    </li>
                    <li class="nav-item" id="store_order">
                        <a class="nav-link" href="?page=store_order"><i class="fas fa-list"></i> ออเดอร์</a>
                    </li>
                    <li class="nav-item" id="store_product">
                        <a class="nav-link" href="?page=store_product"><i class="fas fa-boxes"></i> สินค้า</a>
                    </li>
                    <li class="nav-item" id="store_setting">
                        <a class="nav-link" href="?page=store_setting"><i class="fas fa-cog"></i> ตั้งค่าร้านค้า</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!--/.Navbar -->
    <?php
    } else {
    ?>
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
                        <?php
                        if (isset($_SESSION['username'])) {
                            if ($user_info['hasStore']) {
                                echo '<a class="nav-link" href="?page=store_dashboard"><i class="far fa-store-alt"></i> ร้านค้าของฉัน</a>';
                            } else {
                                echo '<a class="nav-link" href="?page=create_store"><i class="far fa-store-alt"></i> ขายสินค้า</a>';
                            }
                        }
                        ?>
                    </li>
                    <!--
                <li class="nav-item" id="event">
                    <a class="nav-link" href="?page=event"><i class="far fa-calendar-star"></i> กิจกรรม</a>
                </li>
                <li class="nav-item" id="auction">
                    <a class="nav-link" href="?page=auction"><i class="far fa-gavel"></i> การประมูล</a>
                </li>
                -->
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href='?page=cart'>
                            <i class="far fa-shopping-cart"></i>
                            <sup class="sup-shopping-cart"><span id='item-in-cart' class="badge badge-success"></span></sup> ตะกร้าสินค้า
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light">
                            <i class="far fa-bell"></i> การแจ้งเตือน
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php if (isset($_SESSION['username'])) {
                                                echo 'dropdown-toggle';
                                            } ?>" <?php if (isset($_SESSION['username'])) {
                                                        echo 'id="navbarDropdownMenuLink-333" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
                                                    } else {
                                                        echo 'href="?page=login"';
                                                    } ?>>
                            <i class="fas fa-user"></i> บัญชีของฉัน
                        </a>
                        <?php
                        if (isset($_SESSION['username'])) { ?>
                            <div class="dropdown-menu dropdown-menu-right dropdown-default" aria-labelledby="navbarDropdownMenuLink-333">
                                <a class="dropdown-item" href="?page=change_password"><i class="far fa-sliders-v-square"></i> เปลี่ยนรหัสผ่าน</a>
                                <a class="dropdown-item" href="#"><i class="far fa-box"></i> รายการสั่งซื้อของฉัน</a>
                                <a class="dropdown-item" href="?page=product_favorite"><i class="far fa-heart"></i> รายการสินค้าที่ฉันชอบ</a>
                                <a class="dropdown-item" href="#"><i class="far fa-star"></i> รายการร้านค้าที่ฉันติดตาม</a>
                                <a class="dropdown-item" href="#"><i class="far fa-comment"></i> ประวัติการรีวิวสินค้า</a>
                                <a class="dropdown-item" href="?page=logout"><i class="far fa-sign-out"></i> ออกจากระบบ</a>
                            </div>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!--/.Navbar -->
    <?php
    }
    ?>
    <div class="container-md">
        <?php
        if ($_GET["page"] == "home") {
            include_once __DIR__ . '/page/home.php';
        } elseif ($_GET['page'] == "product") {
            include_once __DIR__ . '/page/product.php';
        } elseif ($_GET['page'] == "product_favorite") {
            include_once __DIR__ . '/page/product_favorite.php';
        } elseif ($_GET['page'] == "checkout") {
            include_once __DIR__ . '/page/checkout.php';
        } elseif ($_GET['page'] == "event") {
            include_once __DIR__ . '/page/event.php';
        } elseif ($_GET['page'] == "cart") {
            include_once __DIR__ . '/page/cart.php';
        } elseif ($_GET['page'] == "create_store") {
            include_once __DIR__ . '/page/create_store.php';
        } elseif ($_GET['page'] == "store") {
            include_once __DIR__ . '/page/store.php';
        } elseif ($_GET['page'] == "store_dashboard") {
            include_once __DIR__ . '/page/store_dashboard.php';
        } elseif ($_GET['page'] == "store_order") {
            include_once __DIR__ . '/page/store_order.php';
        } elseif ($_GET['page'] == "store_product") {
            include_once __DIR__ . '/page/store_product.php';
        } elseif ($_GET['page'] == "store_setting") {
            include_once __DIR__ . '/page/store_setting.php';
        } elseif ($_GET['page'] == "register") {
            include_once __DIR__ . '/page/register.php';
        } elseif ($_GET['page'] == "login") {
            include_once __DIR__ . '/page/login.php';
        } elseif ($_GET['page'] == "logout") {
            include_once __DIR__ . '/page/logout.php';
        } elseif ($_GET['page'] == "account") {
            include_once __DIR__ . '/page/account.php';
        } elseif ($_GET['page'] == "change_password") {
            include_once __DIR__ . '/page/change_password.php';
        } else {
            echo '<br>';
            echo '<div class="container"><div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> ไม่พบหน้าที่ท่านร้องขอ กำลังพาท่านกลับไปหน้าหลัก...</div></div>';
            echo '<meta http-equiv="refresh" content="3;URL=?page=home"';
        } ?>
    </div>
    <br>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
    <script defer src="asset/js/popper.js"></script>
    <!-- Star Rating -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.0/css/star-rating.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.0/themes/krajee-svg/theme.css" media="all" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.0/js/star-rating.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.0/themes/krajee-svg/theme.js"></script>
</body>

<!-- Footer -->
<footer class="page-footer font-small teal" style='padding-top: 2rem;'>
    <div class="container-fluid text-md-left">
        <div class="row">
            <div class="col-sm-6">
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
            <div class="col-sm-6">
                <b>เกี่ยวกับเรา</b>
                <ul>
                    <li>เกี่ยวกับเรา</li>
                    <li>ร่วมงานกับเรา</li>
                    <li>นโยบายความเป็นส่วนตัว</li>
                    <li>Seller Center</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright text-center py-3" style="background-color:#2f3133!important">
        <small style="font-size:14px; color: white;">Copyright © <?php echo date("Y"); ?> TPS Marketplace</small>
    </div>
    </div>
</footer>

</html>