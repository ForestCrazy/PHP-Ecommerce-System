<?php
if (isset($_SESSION['username'])) {
?>
    <script>
        window.location.href = '?page=home';
    </script>
<?php
} else { ?>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <!-- Default form register -->
            <form class="text-center border border-light p-5" method='post'>

                <p class="h4 mb-4">สร้างบัญชี</p>

                <!-- username -->
                <input type="text" name='username' id="defaultRegisterFormusername" class="form-control mb-4" placeholder="ชื่อผู้ใช้" required>

                <!-- Password -->
                <input type="password" name='password' id="defaultRegisterFormPassword" class="form-control mb-4" placeholder="รหัสผ่าน" required>

                <!-- Sign in button -->
                <button class="btn btn-green btn-block my-4" type="submit" name='submit_register'>สร้างบัญชี</button>

                <!-- Register -->
                <p>มีบัญชีอยู่แล้ว?
                    <a href="?page=login">คลิกเพื่อไปหน้าเข้าสู่ระบบ</a>
                </p>

                <!-- Social register -->
                <!--
            <p>หรือเข้าสู่ระบบด้วย:</p>

            <a href="#" class="mx-2" role="button"><i class="fab fa-facebook-f light-blue-text"></i></a>
            <a href="#" class="mx-2" role="button"><i class="fab fa-google light-blue-text"></i></a>
            -->

            </form>
            <!-- Default form register -->
        </div>
        <div class="col-sm-3"></div>
    </div>

    <?php
    if (isset($_POST['submit_register'])) {
        try {
            $sql_register = 'INSERT INTO user (username, password) VALUES ("' . mysqli_real_escape_string($connect, $_POST['username']) . '", "' . hash('sha256', $_POST['password']) . '")';
            $res_register = mysqli_query($connect, $sql_register);
            if ($res_register) {
                $alrt_icon = 'success';
                $alrt_title = 'สร้างบัญชีสำเร็จ';
                $alrt_text = 'สร้างบัญชีสำเร็จ';
            } else {
                $alrt_icon = 'error';
                $alrt_title = 'เกิดข้อผิดพลาดในการสร้างบัญชี';
                $alrt_text = 'เกิดข้อผิดพลาดที่ฝั่งเซิร์ฟเวอร์';
            }
    ?>
            <script>
                Swal.fire(
                    '<?php echo $alrt_title ?>',
                    '<?php echo $alrt_text ?>',
                    '<?php echo $alrt_icon ?>',
                ).then((value) => {
                    window.location.href = '?page=login';
                });
            </script>
<?php
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>