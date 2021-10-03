<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    if (isAdmin($_SESSION['u_id'])) {
        if (!isset($_GET['admin'])) {
            $_GET['admin'] = 'manage_account';
        }
        if ($_GET['admin'] == 'manage_account') {
            include_once __DIR__ . '/admin/manage_account.php';
        } elseif ($_GET['admin'] == 'manage_order') {
            include_once __DIR__ . '/admin/manage_order.php';
        } elseif ($_GET['admin'] == 'manage_withdraw') {
            include_once __DIR__ . '/admin/manage_withdraw.php';
        } elseif ($_GET['admin'] == 'approve_payment') {
            include_once __DIR__ . '/admin/approve_payment.php';
        } elseif ($_GET['admin'] == 'manage_banner') {
            include_once __DIR__ . '/admin/manage_banner.php';
        } else {
            gotoPage('admin');
        }
    } else {
        gotoPage('home');
    }
}
