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
            <label>ชื่อร้านค้า</label>
            <input type='text' name='store_name' class='form-control' placeholder='ชื่อร้านค้า' required />
            <br />
            <button type='submit' name='submit_create_store' class='btn btn-success col-12' style='margin: 0!important;'>ยืนยัน</button>
        </form>
        <?php
        if (isset($_POST['submit_create_store'])) {
            $sql_create_store = 'INSERT INTO store (store_name, u_id) VALUES ("' . mysqli_real_escape_string($connect, $_POST['store_name']) . '", "' . $_SESSION['u_id'] . '")';
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
