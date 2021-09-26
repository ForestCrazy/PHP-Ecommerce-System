<?php
if (!$_SESSION['username']) {
    gotoPage('home');
} else {
    if (hasOwnStore($_SESSION['u_id'])) {
        $sql_store_info = 'SELECT * FROM store WHERE u_id = "' . hasOwnStore($_SESSION['u_id'], true) . '"';
        $res_store_info = mysqli_query($connect, $sql_store_info);
        if ($res_store_info) {
            $fetch_store_info = mysqli_fetch_assoc($res_store_info);
?>
            <script>
                var cacheStoreProfile = null;
                var cacheDefaultStoreProfile = null;

                function inputStoreProfile(input) {
                    if (input.files.length === 1) {
                        cacheStoreProfile = input.files[0];
                        cacheDefaultStoreProfile = $('#storeProfile').attr('src');
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#storeProfile').attr('src', e.target.result);
                            $('#inputStoreProfileBtnSection').removeClass('d-block');
                            $('#inputStoreProfileBtnSection').addClass('d-none');
                            $('#storeProfileActionSection').removeClass('d-none');
                            $('#storeProfileActionSection').addClass('d-block');
                        }
                        reader.readAsDataURL(input.files[0]);
                    } else {
                        console.error('file input not found');
                    }
                }

                function confirmChangeStoreProfile() {
                    if (cacheStoreProfile) {
                        var formData = new FormData();
                        formData.append('store_profile', cacheStoreProfile);
                        $.ajax({
                            url: '/API/updateStoreProfile.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(res) {
                                var resp = JSON.parse(res);
                                if (resp.success) {
                                    $('#inputStoreProfileBtnSection').removeClass('d-none');
                                    $('#inputStoreProfileBtnSection').addClass('d-block');
                                    $('#storeProfileActionSection').removeClass('d-block');
                                    $('#storeProfileActionSection').addClass('d-none');
                                }
                            }
                        })
                    }
                }

                function cancelChangeStoreProfile() {
                    if (cacheDefaultStoreProfile) {
                        $('#storeProfile').attr('src', cacheDefaultStoreProfile);
                        $('#inputStoreProfileBtnSection').removeClass('d-none');
                        $('#inputStoreProfileBtnSection').addClass('d-block');
                        $('#storeProfileActionSection').removeClass('d-block');
                        $('#storeProfileActionSection').addClass('d-none');
                    }
                }

                function updateStoreDetail() {
                    $.post('/API/updateStoreDetail.php', {
                        store_name: $('#store_name').val(),
                        store_description: $('#store_description').val(),
                        store_address: $('#store_address').val(),
                        store_city: $('#store_city').val(),
                        store_province: $('#store_province').val(),
                        store_zip_code: $('#store_zip_code').val()
                    }, function(res) {
                        var resp = JSON.parse(res);
                        if (resp.success) {
                            Swal.fire('อัพเดทข้อมูลสำเร็จ');
                        } else {
                            console.error(resp.error ? resp.error : 'failed to update store detail');
                        }
                    })
                }
            </script>
            <div class='col col-top'>
                <div class='card p-3'>
                    <div class='card-body'>
                        <div class='text-center'>
                            <img class="z-depth-2 img-fluid" style="max-height: 150px;" id='storeProfile' src="<?= $fetch_store_info['store_img'] ?>">
                        </div>
                        <div class='d-block' id='inputStoreProfileBtnSection'>
                            <div class='d-flex justify-content-center'>
                                <div class='btn btn-outline-primary d-block' id='inputStoreProfileBtn' onclick="$('#inputStoreProfile').click();">เลือกรูป</div>
                                <input type='file' class='d-none' id='inputStoreProfile' onchange='inputStoreProfile(this)' accept=".png, .jpg, .jpeg" />
                            </div>
                        </div>
                        <div class='d-none' id='storeProfileActionSection'>
                            <div class='d-flex justify-content-center'>
                                <div class='btn btn-outline-danger' onclick='cancelChangeStoreProfile()'>ยกเลิก</div>
                                <div class='btn btn-outline-success' onclick='confirmChangeStoreProfile()'>ยืนยัน</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ชื่อร้านค้า</label>
                            <input type='text' name='store_name' id='store_name' class='form-control' placeholder='ชื่อร้านค้า' value='<?= $fetch_store_info['store_name'] ?>' required />
                        </div>
                        <div class="form-group">
                            <label>คำอธิบาย</label>
                            <textarea name='store_description' id='store_description' class='form-control' placeholder='คำอธิบาย' value='<?= $fetch_store_info['store_description'] ?>' required></textarea>

                        </div>
                        <div class="form-group">
                            <label>ที่อยู่ร้านค้า</label>
                            <input type='text' name='store_address' id='store_address' class='form-control' placeholder='ที่อยู่ร้านค้า' value='<?= $fetch_store_info['store_address'] ?>' required />
                        </div>
                        <div class='row'>
                            <div class='col-lg'>
                                <div class="form-group">
                                    <label>อำเภอ</label>
                                    <input type='text' name='store_city' id='store_city' class='form-control' placeholder='อำเภอ' value='<?= $fetch_store_info['store_city'] ?>' required />
                                </div>
                            </div>
                            <div class='col-lg'>
                                <div class="form-group">
                                    <label>จังหวัด</label>
                                    <input type='text' name='store_province' id='store_province' class='form-control' placeholder='จังหวัด' value='<?= $fetch_store_info['store_province'] ?>' required />
                                </div>
                            </div>
                            <div class='col-lg'>
                                <div class="form-group">
                                    <label>รหัสไปรษณีย์</label>
                                    <input type='text' name='store_zip_code' id='store_zip_code' class='form-control' placeholder='รหัสไปรษณีย์' value='<?= $fetch_store_info['store_zip_code'] ?>' required />
                                </div>
                            </div>
                        </div>
                        <br />
                        <div class='btn btn-success col' onclick='updateStoreDetail()'>
                            บันทึก
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
}
