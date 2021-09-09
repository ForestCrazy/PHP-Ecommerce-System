<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
?>
    <script>
        function updateAddress(addressId) {
            var firstName = $('#first-name-' + addressId).val();
            var lastName = $('#last-name-' + addressId).val();
            var phone = $('#phone-' + addressId).val();
            var address = $('#address-' + addressId).val();
            var city = $('#city-' + addressId).val();
            var province = $('#province-' + addressId).val();
            var zipCode = $('#zip-code-' + addressId).val();

            var formData = new FormData();
            formData.append('address_id', addressId);
            formData.append('firstname', firstName);
            formData.append('lastname', lastName);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('city', city);
            formData.append('province', province);
            formData.append('zip_code', zipCode);
            $.ajax({
                url: '/API/updateAddressDetail.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        Swal.fire(
                            'แก้ไขที่อยู่สำเร็จ',
                            '',
                            'success'
                        ).then(() => {
                            window.location.href = window.location.href;
                        })
                    }
                }
            })
        }

        function createAddress() {
            var firstName = $('#first-name').val();
            var lastName = $('#last-name').val();
            var phone = $('#phone').val();
            var address = $('#address').val();
            var city = $('#city').val();
            var province = $('#province').val();
            var zipCode = $('#zip-code').val();

            var formData = new FormData();
            formData.append('firstname', firstName);
            formData.append('lastname', lastName);
            formData.append('phone', phone);
            formData.append('address', address);
            formData.append('city', city);
            formData.append('province', province);
            formData.append('zip_code', zipCode);
            $.ajax({
                url: '/API/createAddress.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    var resp = JSON.parse(res);
                    if (resp.success) {
                        Swal.fire(
                            'เพิ่มที่อยู่สำเร็จ',
                            '',
                            'success'
                        ).then(() => {
                            window.location.href = window.location.href;
                        })
                    } else {
                        Swal.fire(
                            'เกิดข้อผิดพลาด',
                            'เพิ่มที่อยู่ไม่สำเร็จ',
                            'error'
                        )
                    }
                }
            })
        }
    </script>
    <div class='col'>
        <div class='col text-right'>
            <button class='btn btn-outline-success' type="button" data-toggle="collapse" data-target="#collapse-address" aria-expanded="false" aria-controls="collapse-address">เพิ่มที่อยู่</button>
            <div class='collapse text-left' id='collapse-address'>
                <div class='row'>
                    <div class='col-md-4 form-group'>
                        <label>ชื่อ</label>
                        <input type='text' class='form-control' name='first-name' id='first-name' />
                    </div>
                    <div class='col-md-4 form-group'>
                        <label>นามสกุล</label>
                        <input type='text' class='form-control' name='last-name' id='last-name' />
                    </div>
                    <div class='col-md-4 form-group'>
                        <label>เบอร์โทร</label>
                        <input type='text' class='form-control' name='phone' id='phone' />
                    </div>
                    <div class='col-md-9 form-group'>
                        <label>ที่อยู่</label>
                        <input type='text' class='form-control' name='address' id='address' />
                    </div>
                    <div class='col-md-3 form-group'>
                        <label>อำเภอ</label>
                        <input type='text' class='form-control' name='city' id='city' />
                    </div>
                    <div class='col-md-6 form-group'>
                        <label>จังหวัด</label>
                        <input type='text' class='form-control' name='province' id='province' />
                    </div>
                    <div class='col-md-6 form-group'>
                        <label>รหัสไปรษณีย์</label>
                        <input type='text' class='form-control' name='zip-code' id='zip-code' />
                    </div>
                    <div class='col btn btn-success' onclick="createAddress()">
                        บันทึก
                    </div>
                </div>
            </div>
        </div>
        <?php
        $sql_address = 'SELECT * FROM address WHERE u_id = "' . $_SESSION['u_id'] . '"';
        $res_address = mysqli_query($connect, $sql_address);
        if ($res_address) {
            while ($fetch_address = mysqli_fetch_assoc($res_address)) {
        ?>
                <div class='col col-top'>
                    <div class='card'>
                        <div class='card-body'>
                            <div class='row'>
                                <div class='col-md-8'>
                                    <?= $fetch_address['first_name'] . ' ' . $fetch_address['last_name'] . ' ' . $fetch_address['phone'] . '&emsp;' . $fetch_address['address'] . ', อำเภอ ' . $fetch_address['city'] . ', จังหวัด ' . $fetch_address['province'] . ', รหัสไปรษณีย์ ' . $fetch_address['zip_code'] ?>
                                </div>
                                <div class='col-md-4 text-right'>
                                    <button class='btn btn-danger' type="button" data-toggle="collapse" data-target="#collapse-address-<?= $fetch_address['address_id'] ?>" aria-expanded="false" aria-controls="collapse-address-<?= $fetch_address['address_id'] ?>">แก้ไข</button>
                                </div>
                            </div>
                            <div class='collapse' id='collapse-address-<?= $fetch_address['address_id'] ?>'>
                                <div class='row'>
                                    <div class='col-md-4 form-group'>
                                        <label>ชื่อ</label>
                                        <input type='text' class='form-control' name='first-name-<?= $fetch_address['address_id'] ?>' id='first-name-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['first_name'] ?>' />
                                    </div>
                                    <div class='col-md-4 form-group'>
                                        <label>นามสกุล</label>
                                        <input type='text' class='form-control' name='last-name-<?= $fetch_address['address_id'] ?>' id='last-name-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['last_name'] ?>' />
                                    </div>
                                    <div class='col-md-4 form-group'>
                                        <label>เบอร์โทร</label>
                                        <input type='text' class='form-control' name='phone-<?= $fetch_address['address_id'] ?>' id='phone-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['phone'] ?>' />
                                    </div>
                                    <div class='col-md-9 form-group'>
                                        <label>ที่อยู่</label>
                                        <input type='text' class='form-control' name='address-<?= $fetch_address['address_id'] ?>' id='address-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['address'] ?>' />
                                    </div>
                                    <div class='col-md-3 form-group'>
                                        <label>อำเภอ</label>
                                        <input type='text' class='form-control' name='city-<?= $fetch_address['address_id'] ?>' id='city-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['city'] ?>' />
                                    </div>
                                    <div class='col-md-6 form-group'>
                                        <label>จังหวัด</label>
                                        <input type='text' class='form-control' name='province-<?= $fetch_address['address_id'] ?>' id='province-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['province'] ?>' />
                                    </div>
                                    <div class='col-md-6 form-group'>
                                        <label>รหัสไปรษณีย์</label>
                                        <input type='text' class='form-control' name='zip-code-<?= $fetch_address['address_id'] ?>' id='zip-code-<?= $fetch_address['address_id'] ?>' value='<?= $fetch_address['zip_code'] ?>' />
                                    </div>
                                    <div class='col btn btn-success' onclick="updateAddress(<?= $fetch_address['address_id'] ?>)">
                                        บันทึก
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
<?php
}
