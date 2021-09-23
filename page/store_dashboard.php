<?php
if (!isset($_SESSION['username'])) {
?>
    <script>
        window.location.href = '?page=login';
    </script>
    <?php
} else {
    if (!$user_info['hasStore']) {
    ?>
        <script>
            window.location.href = '?page=home';
        </script>
    <?php
    } else {
        $sql_order = 'SELECT COUNT(order.order_id) AS order_amount FROM (SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status != "pending"';
    ?>
        <style>
            .chart {
                max-height: 300px !important;
            }
        </style>
        <div class='row'>
            <div class='col-md-4 col-top'>
                <div class='card'>
                    <div class='card-body'>
                        <small>TODAY</small>
                        <?php
                        $sql_order_today = $sql_order . ' AND DATE(order.createtime) = DATE(NOW()) GROUP BY order.order_id) AS `order`';
                        $res_order_today = mysqli_query($connect, $sql_order_today);
                        if ($res_order_today) {
                            $fetch_order_today = mysqli_fetch_assoc($res_order_today);
                        ?>
                            <h3><?= $fetch_order_today['order_amount'] ?> Orders</h3>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-top'>
                <div class='card'>
                    <div class='card-body'>
                        <small>MONTH</small>
                        <?php
                        $sql_order_month = $sql_order . ' AND MONTH(order.createtime) = MONTH(NOW()) AND YEAR(order.createtime) = YEAR(NOW()) GROUP BY order.order_id) AS `order`';
                        $res_order_month = mysqli_query($connect, $sql_order_month);
                        if ($res_order_month) {
                            $fetch_order_month = mysqli_fetch_assoc($res_order_month);
                        ?>
                            <h3><?= $fetch_order_month['order_amount'] ?> Orders</h3>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class='col-md-4 col-top'>
                <div class='card'>
                    <div class='card-body'>
                        <small>YEAR</small>
                        <?php
                        $sql_order_year = $sql_order . ' AND YEAR(order.createtime) = YEAR(NOW()) GROUP BY order.order_id) AS `order`';
                        $res_order_year = mysqli_query($connect, $sql_order_year);
                        if ($res_order_year) {
                            $fetch_order_year = mysqli_fetch_assoc($res_order_year);
                        ?>
                            <h3><?= $fetch_order_year['order_amount'] ?> Orders</h3>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class='row'>
            <!-- <div class='col-md-6'>
                <div class='card'>
                    <div style="margin-top: 10px; padding-right: 15px;">
                        <div class="p-2 bd-highlight">Order History</div>
                    </div>
                    <div class='card-body'>
                        <canvas id="order_history" class='chart' width="400" height="400"></canvas>
                        <script>
                            var ctx = document.getElementById('order_history');
                            var order_history = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                                    datasets: [{
                                        label: 'My First Dataset',
                                        data: [65, 59, 80, 81, 56, 55, 40],
                                        fill: false,
                                        borderColor: 'rgb(75, 192, 192)',
                                        tension: 0.1
                                    }]
                                }
                            });
                        </script>
                    </div>
                </div>
            </div> -->
            <div class='col-md-6'>
                <div class='card'>
                    <div style="margin-top: 10px; padding-right: 15px;">
                        <div class="p-2 bd-highlight">Order Status</div>
                    </div>
                    <div class='card-body'>
                        <div class='row'>
                            <canvas id="order_status" class='chart' width="400" height="400"></canvas>
                            <?php
                            $sql_order_success = 'SELECT COUNT(order.order_id) AS order_amount FROM (SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status = "success") AS `order`';
                            $res_order_success = mysqli_query($connect, $sql_order_success);
                            if ($res_order_success) {
                                $fetch_order_success = mysqli_fetch_assoc($res_order_success);
                            }
                            $sql_order_processing = 'SELECT COUNT(order.order_id) AS order_amount FROM (SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status = "processing" AND track_code IS NULL) AS `order`';
                            $res_order_processing = mysqli_query($connect, $sql_order_processing);
                            if ($res_order_processing) {
                                $fetch_order_processing = mysqli_fetch_assoc($res_order_processing);
                            }
                            $sql_order_shipping = 'SELECT COUNT(order.order_id) AS order_amount FROM (SELECT order.order_id FROM `order` INNER JOIN sub_order ON order.order_id = sub_order.order_id INNER JOIN product ON sub_order.product_id = product.product_id WHERE product.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '" AND order.status = "processing" AND track_code IS NOT NULL) AS `order`';
                            $res_order_shipping = mysqli_query($connect, $sql_order_shipping);
                            if ($res_order_shipping) {
                                $fetch_order_shipping = mysqli_fetch_assoc($res_order_shipping);
                            }
                            ?>
                            <script>
                                var ctx = document.getElementById('order_status');
                                var order_status = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: [
                                            'ออเดอร์ที่สำเร็จ',
                                            'รอการจัดส่ง',
                                            'อยู่ระหว่างการจัดส่ง'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [<?= $fetch_order_success['order_amount'] ?>, <?= $fetch_order_processing['order_amount'] ?>, <?= $fetch_order_shipping['order_amount'] ?>],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(54, 162, 235)',
                                                'rgb(255, 205, 86)'
                                            ],
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
