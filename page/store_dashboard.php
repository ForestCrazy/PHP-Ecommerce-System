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
        $sql_store_balance = 'SELECT store_balance FROM store WHERE store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '"';
        $res_store_balance = mysqli_query($connect, $sql_store_balance);
        if ($res_store_balance) {
            $fetch_store_balance = mysqli_fetch_assoc($res_store_balance);
        }
    ?>
        <style>
            .chart {
                max-height: 300px !important;
            }
        </style>
        <script>
            function toggleWithdrawModal() {
                $('#withdrawRequestModal').modal('show');
            }

            $(function() {
                $('#myTab li:first-child a').tab('show')
            })

            function calculateTransferAmount(input) {
                var transfer_amount = input.value - float2int((input.value / 100 * 3));
                $('#transfer-amount').val(transfer_amount);
            }

            function float2int(value) {
                return value | 0;
            }

            function withdrawRequest() {
                var withdraw_amount = $('#withdraw-amount').val();
                var account_number = $('#account-number').val();
                var bank_id = $('#bank-id').val();
                $.post('/API/createWithdrawRequest.php', {
                    withdraw_amount: withdraw_amount,
                    account_number: account_number,
                    bank_id: bank_id
                }, function(res) {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        $('#withdrawRequestModal').modal('hide');
                        location.reload();
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to create withdraw request');
                        if (resp.reason) {
                            Swal.fire(resp.reason);
                        }
                    }
                })
            }
        </script>
        <div class="modal fade" id="withdrawRequestModal" tabindex="-1" role="dialog" aria-labelledby="withdrawRequestLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawRequestLabel">ถอนเงิน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item col">
                                <a class="nav-link text-center" id="withdraw-tab" data-toggle="tab" href="#withdraw" role="tab" aria-controls="withdraw" aria-selected="true">ขอถอนเงิน</a>
                            </li>
                            <li class="nav-item col">
                                <a class="nav-link text-center" id="history-withdraw-tab" data-toggle="tab" href="#history-withdraw" role="tab" aria-controls="history-withdraw" aria-selected="false">ประวัติการถอนเงิน</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="withdraw" role="tabpanel" aria-labelledby="withdraw-tab">
                                <div class='col col-top'>
                                    <span class='font-weight-bold'>จำนวนเงินที่ต้องการถอน (จำนวนเงินที่ถอนได้ : <?= $fetch_store_balance['store_balance'] ?>)</span>
                                    <input type='number' class='form-control' id='withdraw-amount' max='<?= $fetch_store_balance['store_balance'] ?>' min='20' onchange='calculateTransferAmount(this)' />
                                    <span class='font-weight-bold'>จำนวนเงินที่ได้หลังหักค่าธรรมเนียม</span>
                                    <input type='number' class='form-control' id='transfer-amount' readonly />
                                    <span class='font-weight-bold'>หมายเลขบัญชี</span>
                                    <input type='text' class='form-control' id='account-number' />
                                    <span class='font-weight-bold'>ธนาคาร</span>
                                    <select class="browser-default custom-select" id='bank-id'>
                                        <option selected>เลือกธนาคาร</option>
                                        <?php
                                        $sql_bank_withdraw = 'SELECT * FROM bank_withdraw WHERE enable = 1';
                                        $res_bank_withdraw = mysqli_query($connect, $sql_bank_withdraw);
                                        if ($res_bank_withdraw) {
                                            while ($fetch_bank_withdraw = mysqli_fetch_assoc($res_bank_withdraw)) {
                                        ?>
                                                <option value='<?= $fetch_bank_withdraw['bank_id'] ?>'><?= $fetch_bank_withdraw['bank_name'] ?> (<?= $fetch_bank_withdraw['bank_code'] ?>)</option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="tab-pane" id="history-withdraw" role="tabpanel" aria-labelledby="history-withdraw-tab">
                                <?php
                                $sql_withdraw = 'SELECT * FROM withdraw_request INNER JOIN bank_withdraw ON withdraw_request.bank_id = bank_withdraw.bank_id WHERE withdraw_request.store_id = "' . hasOwnStore($_SESSION['u_id'], true) . '"';
                                $res_withdraw = mysqli_query($connect, $sql_withdraw);
                                if ($res_withdraw) {
                                    $loop = mysqli_num_rows($res_withdraw);
                                    $current_loop = 0;
                                    while ($fetch_withdraw = mysqli_fetch_assoc($res_withdraw)) {
                                        $current_loop = $current_loop + 1;
                                ?>
                                        <div class='col col-top'>
                                            <span><?= $fetch_withdraw['createtime'] ?> | <?= $fetch_withdraw['withdraw_balance'] ?>฿ (<?= $fetch_withdraw['withdraw_balance'] + $fetch_withdraw['fees'] ?>฿)</span>
                                            <span>สถานะ : <?php
                                                            switch ($fetch_withdraw['status']) {
                                                                case 'pending':
                                                                    echo 'รออนุมัติ';
                                                                    break;
                                                                case 'approve':
                                                                    echo 'อนุมัติ';
                                                                    break;
                                                                case 'decline':
                                                                    echo 'ปฏิเสธ';
                                                                    break;
                                                            }
                                                            ?>
                                            </span>
                                        </div>
                                        <?php
                                        if ($loop > $current_loop) {
                                        ?>
                                            <hr />
                                        <?php
                                        }
                                        ?>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-primary" onclick='withdrawRequest()'>ส่งคำขอการถอนเงิน</button>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-3 col-top'>
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
            <div class='col-md-3 col-top'>
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
            <div class='col-md-3 col-top'>
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
            <div class='col-md-3 col-top'>
                <div class='card'>
                    <div class='card-body'>
                        <small>Balance</small>
                        <?php
                        if ($res_store_balance) {
                        ?>
                            <h3>฿<?= $fetch_store_balance['store_balance'] ?> <div class='btn btn-success d-inline' style='padding: .375rem .75rem!important;' onclick='toggleWithdrawModal()'>ถอนเงิน</div>
                            </h3>
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
