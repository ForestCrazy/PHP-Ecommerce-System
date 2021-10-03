<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $sql_withdraw = 'SELECT * FROM withdraw_request INNER JOIN bank_withdraw ON withdraw_request.bank_id = bank_withdraw.bank_id WHERE status = "pending"';
    $res_withdraw = mysqli_query($connect, $sql_withdraw);
    if ($res_withdraw) {
?>
        <script>
            var cacheWithdrawSlip = null;

            $(document).ready(function() {
                $('#updateWithdrawSlipModal').on('hide.bs.modal', function() {
                    $('#update-withdraw-id').val(0);
                    $('#preview-withdraw-slip').attr('src', '');
                    $('#updateWIthdrawSlip').val(null);
                });
            });

            function updateWithdrawSlip(input) {
                if (input.files.length == 1) {
                    cacheWithdrawSlip = input.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-withdraw-slip').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    console.error('failed to preview payment slip');
                }
            }

            function toggleWithdrawSlipModal(withdraw_id) {
                $('#updateWithdrawSlipModal').modal('show');
                $('#update-withdraw-id').val(withdraw_id);
            }

            function updatePaymentSlip(status) {
                var withdraw_id = $('#update-withdraw-id').val();
                if (withdraw_id !== '') {
                    if (cacheWithdrawSlip || status == 'decline') {
                        var formData = new FormData();
                        formData.append('withdraw_id', withdraw_id);
                        formData.append('withdrawSlip', cacheWithdrawSlip);
                        formData.append('status', status);
                        $.ajax({
                            url: '/API/updateWithdrawStatus.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(res) {
                                var resp = JSON.parse(res);
                                if (resp.success) {
                                    $('#updateWIthdrawSlipModal').modal('hide');
                                    console.log('update payment slip success');
                                    location.reload();
                                } else {
                                    console.error(resp.reason ? resp.reason : 'failed to update payment slip');
                                }
                            }
                        })
                    } else {
                        console.error('failed to update withdraw status');
                    }
                }
            }
        </script>
        <div class="modal fade" id="updateWithdrawSlipModal" tabindex="-1" role="dialog" aria-labelledby="updateWIthdrawSlipLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateWIthdrawSlipLabel">อัพเดทการถอนเงิน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class='col col-top font-weight-bold text-center'>
                            <div id='transfer-amount'></div>
                            <div class='text-center'>
                                <img id='preview-withdraw-slip' class='col' style='min-height: 10rem; max-height: 280px;' src='' />
                            </div>
                            <div class='d-block'>
                                <div class='d-flex justify-content-center'>
                                    <div class='btn btn-outline-primary d-block' onclick="$('#updateWithdrawSlip').click();">เลือกรูป</div>
                                    <input type='file' class='d-none' id='updateWithdrawSlip' onchange='updateWithdrawSlip(this)' accept=".png, .jpg, .jpeg" />
                                </div>
                                <input type='hidden' id='update-withdraw-id' />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="button" class="btn btn-danger" onclick='updatePaymentSlip("decline")'>ปฏิเสธ</button>
                        <button type="button" class="btn btn-primary" onclick='updatePaymentSlip("approve")'>อัพเดทหลักฐานการโอนเงิน</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Store ID</th>
                        <th scope="col">Withdraw Amount</th>
                        <th scope="col">Fees</th>
                        <th scope="col">Bank Endpoint</th>
                        <th scope="col">Createtime</th>
                        <th scope="col">Tools</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fetch_withdraw = mysqli_fetch_assoc($res_withdraw)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $fetch_withdraw['withdraw_id'] ?></th>
                            <td><?= $fetch_withdraw['store_id'] ?></td>
                            <td id='withdraw-amount-<?= $fetch_withdraw['withdraw_id'] ?>'><?= $fetch_withdraw['withdraw_balance'] ?></td>
                            <td><?= $fetch_withdraw['fees'] ?></td>
                            <td><?= $fetch_withdraw['bank_name'] ?></td>
                            <td><?= $fetch_withdraw['createtime'] ?></td>
                            <td>
                                <div class='btn btn-primary' onclick='toggleWithdrawSlipModal(<?= $fetch_withdraw['withdraw_id'] ?>)'><i class="fas fa-cog"></i></div>
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
