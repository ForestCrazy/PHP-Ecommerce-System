<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <script>
        $(document).ready(function() {
            $('#product-review').on('rating:hover', function(event, value, caption, target) {
                $('#review-score').html('&emsp;' + value + ' คะแนน');
            });

            $('#product-review').on('rating:change', function(event, value, caption) {
                $('#review-score').html('&emsp;' + value + ' คะแนน');
            });

            $('#product-review').on('rating:hoverleave', function(event, target) {
                const value = $('#product-review').val() == '' ? 0 : $('#product-review').val();
                $('#review-score').html('&emsp;' + value + ' คะแนน');
            });
        });

        function review_product(p_id) {
            $('#reviewProductModal').modal('show');
            $('#review-sub-order-id').val(p_id);
        }

        function confirmReviewProduct() {
            var sub_order_id = $('#review-sub-order-id').val();
            var score_product = $('#product-review').val();
            var msg_review = $('#review-product-msg').val();
            if (score_product == '') {
                Swal.fire('กรุณากรอกคะแนนสินค้าก่อนทำการรีวิว');
            } else {
                $.get('/API/reviewProduct.php', {
                    sub_order_id: sub_order_id,
                    score_product: score_product,
                    msg_review: msg_review,
                }).then((res) => {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        $('#reviewProductModal').modal('hide');
                        console.log('รีวิวสินค้าสำเร็จ');
                        location.reload();
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to review product');
                    }
                })
            }
        }
    </script>
    <div class="modal fade" id="reviewProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">รีวิวสินค้า</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='col d-flex justifu-content-start align-items-center'>
                        <input id="product-review" class="rating d-none col-top" data-stars="5" data-step="1" title="" data-show-clear='false' />
                        <div id='review-score'>&emsp;0 คะแนน</div>
                    </div>
                    <textarea class='form-control rounded-0 col-top' rows='5' id='review-product-msg' name='review-product-msg' placeholder='อธิบายสินค้านี้เพิ่มเติม'></textarea>
                    <input type='hidden' name='review-sub-order-id' id='review-sub-order-id' />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick='confirmReviewProduct()'>รีวิว</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $sql_order_id = 'SELECT * FROM `order` WHERE u_id = "' . $_SESSION['u_id'] . '"';
    $res_order_id = mysqli_query($connect, $sql_order_id);
    if ($res_order_id) {
        while ($fetch_order_id = mysqli_fetch_assoc($res_order_id)) {
            $sql_order = 'SELECT *, CAST(sub_order.total_price / sub_order.quantity AS INT) AS price_per_item, order.product_price AS total_product_price FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id INNER JOIN store ON product.store_id = store.store_id LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id WHERE order.u_id = "' . $_SESSION['u_id'] . '" AND order.order_id = "' . $fetch_order_id['order_id'] . '"';
            $res_order = mysqli_query($connect, $sql_order);
            if ($res_order) {
                $loop = mysqli_num_rows($res_order);
                $current_loop = 0;
                while ($fetch_order = mysqli_fetch_assoc($res_order)) {
                    if ($current_loop == 0) {
    ?>
                        <div class='card col-top pt-3'>
                            <div class='d-flex justify-content-start align-items-center'>
                                <div class='text-nowrap' style='width: 120px;'>
                                    &emsp;
                                    <i class="far fa-store"></i>
                                    <?= $fetch_order['store_name'] ?>
                                </div>
                                <div class='flex-fill'>
                                    <div class='row'>
                                        <div class='col-4'></div>
                                        <div class='col-8 text-center d-none d-md-block'>
                                            <div class='row'>
                                                <div class='col'>
                                                    ราคาต่อชิ้น
                                                </div>
                                                <div class='col'>
                                                    จำนวน
                                                </div>
                                                <div class='col'>
                                                    รวม
                                                </div>
                                                <div class='col'>
                                                    การรีวิว
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                        ?>
                        <div class='d-flex justify-content-start align-items-center'>
                            <img src='<?= $fetch_order['img_url'] ?>' style='max-height: 120px;' class='img-fluid' />
                            <div class='flex-fill'>
                                <div class='row'>
                                    <div class='col-md-4'>
                                        <span>
                                            <?= $fetch_order['product_name'] ?>
                                        </span>
                                    </div>
                                    <div class='col-md-8 text-md-center'>
                                        <div class='row'>
                                            <div class='col-sm-3'>
                                                <span class='d-inline d-md-none'>
                                                    ราคาต่อชิ้น
                                                </span>
                                                <span class='d-inline d-md-none'>
                                                    :
                                                </span>
                                                <span class='d-inline d-md-block'>
                                                    ฿<?= $fetch_order['price_per_item'] ?>
                                                </span>
                                            </div>
                                            <div class='col-sm-3'>
                                                <span class='d-inline d-md-none'>
                                                    จำนวน
                                                </span>
                                                <span class='d-inline d-md-none'>
                                                    :
                                                </span>
                                                <span class='d-inline d-md-block'>
                                                    <?= $fetch_order['quantity'] ?>
                                                </span>
                                            </div>
                                            <div class='col-sm-3'>
                                                <span class='d-inline d-md-none'>
                                                    รวม
                                                </span>
                                                <span class='d-inline d-md-none'>
                                                    :
                                                </span>
                                                <span class='d-inline d-md-block'>
                                                    ฿<?= $fetch_order['total_price'] ?>
                                                </span>
                                            </div>
                                            <div class='col-sm-3'>
                                                <?php
                                                $sql_product_review = 'SELECT * FROM product_review WHERE sub_order_id = "' . $fetch_order['sub_order_id'] . '"';
                                                $res_product_review = mysqli_query($connect, $sql_product_review);
                                                if ($res_product_review) {
                                                    if (mysqli_num_rows($res_product_review) == 1) {
                                                ?>
                                                        <span>
                                                            รีวิวสินค้านี้แล้ว
                                                        </span>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <span class='text-primary' onclick='review_product(<?= $fetch_order['sub_order_id'] ?>)'>
                                                            รีวิวสินค้า
                                                        </span>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $current_loop += 1;
                        if ($current_loop == $loop) {
                        ?>
                            <div class='alert-secondary col py-1'>
                                <div class='d-flex justify-content-end text-md-right'>
                                    <span>
                                        ยอดคำสั่งซื้อทั้งหมด :
                                        &nbsp;
                                    </span>
                                    <h4 class='text-orange'>
                                        ฿ <?= $fetch_order['total_product_price'] ?>
                                    </h4>
                                </div>
                                <div class='d-flex justify-content-end align-items-center text-center text-md-left'>
                                    <?php
                                    if (empty($fetch_order['end_process_time'])) {
                                    ?>
                                        <div class='btn btn-success'>ยืนยันได้รับสินค้า</div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class='btn btn-primary' onclick='window.location = "?page=product&p_id=<?= $fetch_order['product_id'] ?>"'>ซื้ออีกครั้ง</div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
<?php
                        }
                    }
                }
            }
        }
    }