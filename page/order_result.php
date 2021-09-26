<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <div class='card col-top'>
        <div class='card-body'>
            <div class="d-flex justify-content-center text-success">
                <h2><i class="fas fa-check-circle"></i> คำสั่งซื้อสำเร็จ!</h2>
            </div>
        </div>
    </div>
<?php
}
