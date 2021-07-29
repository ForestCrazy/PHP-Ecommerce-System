<?php
if (!isset($_SESSION['username'])) {
?>
    <script>
        window.location.href = '?page=login';
    </script>
    <?php
} else {
    if ($user_info['hasStore']) {
    ?>
        <script>
            window.location.href = '?page=home';
        </script>
    <?php
    } else {
    ?>
        <form class="border border-light p-5" method='post'>
            <p class="h4 text-center mb-4">สร้างร้านค้า</p>
            <div class="form-group">
                <label>ชื่อร้านค้า</label>
                <input type='text' name='store_name' class='form-control' placeholder='ชื่อร้านค้า' required />
            </div>
            <div class="form-group">
                <label>ที่อยู่ร้านค้า</label>
                <input type='text' name='store_address' class='form-control' placeholder='ที่อยู่ร้านค้า' required />
            </div>
            <div class='row'>
                <div class='col-lg'>
                    <div class="form-group">
                        <label>อำเภอ</label>
                        <input type='text' name='store_city' class='form-control' placeholder='อำเภอ' required />
                    </div>
                </div>
                <div class='col-lg'>
                    <div class="form-group">
                        <label>จังหวัด</label>
                        <input type='text' name='store_province' class='form-control' placeholder='จังหวัด' required />
                    </div>
                </div>
                <div class='col-lg'>
                    <div class="form-group">
                        <label>รหัสไปรษณีย์</label>
                        <input type='text' name='store_zip_code' class='form-control' placeholder='รหัสไปรษณีย์' required />
                    </div>
                </div>
            </div>
            <br />
            <button type='submit' name='submit_create_store' class='btn btn-success col-12' style='margin: 0!important;'>ยืนยัน</button>
        </form>
        <?php
        if (isset($_POST['submit_create_store'])) {
            $sql_create_store = 'INSERT INTO store (store_name, store_address, store_city, store_province, store_zip_code, u_id) VALUES ("' . mysqli_real_escape_string($connect, $_POST['store_name']) . '", "' . mysqli_real_escape_string($connect, $_POST['store_address']) . '", "' . mysqli_real_escape_string($connect, $_POST['store_city']) . '", "' . mysqli_real_escape_string($connect, $_POST['store_province']) . '", "' . mysqli_real_escape_string($connect, $_POST['store_zip_code']) . '", "' . $_SESSION['u_id'] . '")';
            $res_create_store = mysqli_query($connect, $sql_create_store);
            if ($res_create_store) {
                $alrt_icon = 'success';
                $alrt_title = 'สร้างร้านค้าสำเร็จ';
                $alrt_text = 'ทำการสร้างร้านค้าสำเร็จ';
            } else {
                $alrt_icon = 'error';
                $alrt_title = 'เกิดข้อผิดพลาด';
                $alrt_text = 'เกิดข้อผิดพลาดที่ฝั่งเซิร์ฟเวอร์';
            }
        ?>
            <script>
                Swal.fire(
                    '<?php echo $alrt_title ?>',
                    '<?php echo $alrt_text ?>',
                    '<?php echo $alrt_icon ?>',
                ).then((value) => {
                    window.location.href = '?page=store';
                });
            </script>
<?php
        }
    }
}
