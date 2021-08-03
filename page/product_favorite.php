<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <script>
        function removeFavoriteItem(p_id) {
            $.get('API/removeFavoriteItem.php', {
                p_id: p_id
            }, function(res) {
                var resp = JSON.parse(res);
                if (resp['success'] == true) {
                    Swal.fire(
                        'ลบออกจากสินค้าที่ชื่นชอบสำเร็จ',
                        '',
                        'success',
                    ).then(() => {
                        window.location.reload();
                    })
                } else {
                    Swal.fire(
                        'เกิดข้อผิดพลาดในการลบออกจากสินค้าที่ชื่นชอบ',
                        '',
                        'error',
                    ).then(() => {
                        window.location.reload();
                    })
                }
            })
        }
    </script>
    <div class='row col-top'>
        <h3>รายการสินค้าที่ชื่นชอบ</h3>
        <div class='col-12'>
            <?php
            $sql_product_favorite = 'SELECT product_img.img_url, product.product_name, product_favorite.createtime FROM product_favorite INNER JOIN product ON product_favorite.product_id = product.product_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE product_favorite.u_id = "' . $_SESSION['u_id'] . '"';
            $res_product_favorite = mysqli_query($connect, $sql_product_favorite);
            if ($res_product_favorite) {
                while ($fetch_product_favorite = mysqli_fetch_assoc($res_product_favorite)) {
            ?>
                    <div class='col-12 card col-top'>
                        <div class='row'>
                            <div class='col-4 col-xl-2 col-sm-2' style='padding: 0!important;'>
                                <img src='<?= $fetch_product_favorite['img_url'] ?>' style="max-height: 120px;" alt="" class="img-fluid" />
                            </div>
                            <div class='col-6 col-xl-10 col-sm-10'>
                                <div class='col-12 row py-3 px-0'>
                                    <div class='col-12 col-sm-8 col-md-8'>
                                        <h5 class='card-title'><?= $fetch_product_favorite['product_name'] ?></h5>
                                        <br />
                                        <span>
                                            เพิ่มเป็นสินค้าที่ชื่นชอบเมื่อวันที่ <?= $fetch_product_favorite['createtime'] ?>
                                        </span>
                                    </div>
                                    <div class='col-4 col-sm-4 col-md-4 px-0'>
                                        <div class='float-md-right'>
                                            <button class='btn btn-danger' onclick='removeFavoriteItem(<?= $fetch_product_favorite['product_id'] ?>)'>
                                                ลบ
                                            </button>
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
    </div>
<?php
}
