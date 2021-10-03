<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $sql_payment = 'SELECT * FROM payment WHERE status = "pending"';
    $res_payment = mysqli_query($connect, $sql_payment);
    if ($res_payment) {
?>
        <script>
            function togglePaymentSlipCheckModal(payment_id) {
                $('#paymentSlipCheckModal').modal('show');
                $('#payment-id').val(payment_id);
                var pay_slip = $('#payment-' + payment_id).text();
                $('#payment-slip-img').attr('src', pay_slip);
                var transfer_amount = $('#transfer-amount-' + payment_id).text();
                $('#transfer-amount').text('จำนวนเงินที่ต้องชำระ : ' + transfer_amount + ' ฿');
            }

            function updatePaymentSlipStatus(status) {
                var payment_id = $('#payment-id').val();
                $.post('/API/updatePaymentSlipStatus.php', {
                    payment_id: payment_id,
                    status: status
                }, function (res) {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        $('#paymentSLipCheckModal').modal('hide');
                        location.reload();
                    } else {
                        console.error(resp.reason ? resp.reason : 'failed to update payment status');
                    }
                })
            }
        </script>
        <div class="modal fade" id="paymentSlipCheckModal" tabindex="-1" role="dialog" aria-labelledby="paymentSlipCheckLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentSlipCheckLabel">ตรวจสอบการชำระเงิน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div id='transfer-amount'></div>
                        <input type='hidden' id='payment-id' />
                        <img id='payment-slip-img' class='mw-100' style='max-height: 390px;' src='' />
                        <div class='d-block'>
                            <div class='btn btn-success' onclick='updatePaymentSlipStatus("approve")'><i class="far fa-check-circle"></i></div>
                            <div class='btn btn-danger d-inline' onclick='updatePaymentSlipStatus("decline")'><i class="far fa-ban"></i></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Transfer Amount</th>
                        <th scope="col">Transfer Slip</th>
                        <th scope="col">Createtime</th>
                        <th scope="col">Tools</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fetch_payment = mysqli_fetch_assoc($res_payment)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $fetch_payment['payment_id'] ?></th>
                            <td><?= $fetch_payment['u_id'] ?></td>
                            <td><?= $fetch_payment['order_id'] ?></td>
                            <td id='transfer-amount-<?= $fetch_payment['payment_id'] ?>'>
                                <?php
                                $sql_order = 'SELECT shipping_price + product_price AS total_price FROM `order` WHERE order_id = "' . $fetch_payment['order_id'] . '"';
                                $res_order = mysqli_query($connect, $sql_order);
                                if ($res_order) {
                                    $fetch_order = mysqli_fetch_assoc($res_order);
                                    echo $fetch_order['total_price'];
                                }
                                ?>
                            </td>
                            <td id='payment-<?= $fetch_payment['payment_id'] ?>'><?= $fetch_payment['pay_slip'] ?></td>
                            <td><?= $fetch_payment['createtime'] ?></td>
                            <td>
                                <div class='btn btn-primary' onclick='togglePaymentSlipCheckModal(<?= $fetch_payment['payment_id'] ?>)'><i class="fas fa-cog"></i></div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
<?php
    }
}
