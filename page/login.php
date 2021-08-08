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
            <!-- Default form login -->
            <form class="text-center border border-light p-5" method='post'>

                <p class="h4 mb-4">เข้าสู่ระบบ</p>

                <!-- username -->
                <input type="text" name='username' id="defaultLoginFormusername" class="form-control mb-4" placeholder="ชื่อผู้ใช้" required>

                <!-- Password -->
                <input type="password" name='password' id="defaultLoginFormPassword" class="form-control mb-4" placeholder="รหัสผ่าน" required>

                <!-- <div class="d-flex justify-content-around">
                    <div> -->
                        <!-- Remember me -->
                        <!-- <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="defaultLoginFormRemember">
                            <label class="custom-control-label" for="defaultLoginFormRemember">จดจำการเข้าสู่ระบบ</label>
                        </div> -->
                    <!-- </div> -->
                    <!-- <div> -->
                        <!-- Forgot password -->
                        <!-- <a href="">ลืมรหัสผ่าน?</a>
                    </div> -->
                <!-- </div> -->

                <!-- Sign in button -->
                <button class="btn btn-green btn-block my-4" type="submit" name='submit_login'>เข้าสู่ระบบ</button>

                <!-- Register -->
                <p>ยังไม่สมัครสมาชิก?
                    <a href="?page=register">คลิกเพื่อไปสมัครสมาชิก</a>
                </p>

                <!-- Social login -->
                <!--
            <p>หรือเข้าสู่ระบบด้วย:</p>

            <a href="#" class="mx-2" role="button"><i class="fab fa-facebook-f light-blue-text"></i></a>
            <a href="#" class="mx-2" role="button"><i class="fab fa-google light-blue-text"></i></a>
            -->

            </form>
            <!-- Default form login -->
        </div>
        <div class="col-sm-3"></div>
    </div>

    <?php
    if (isset($_POST['submit_login'])) {
        try {
            $sql_login = 'SELECT u_id, username FROM user WHERE username="' . $_POST['username'] . '" AND password="' . hash('sha256', $_POST['password']) . '"';
            $res_login = mysqli_query($connect, $sql_login);
            if ($res_login) {
                $fetch_login = mysqli_fetch_assoc($res_login);
                if (mysqli_num_rows($res_login) == 1) {
                    $_SESSION['u_id'] = $fetch_login['u_id'];
                    $_SESSION['username'] = $fetch_login['username'];

                    $alrt_icon = 'success';
                    $alrt_title = 'ล็อกอินสำเร็จ';
                    $alrt_text = 'เข้าสู่ระบบสำเร็จ กำลังพาท่านไปหน้าหลัก';
                } else {
                    $alrt_icon = 'error';
                    $alrt_title = 'เกิดข้อผิดพลาด';
                    $alrt_text = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                }
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
                    var url = new URL(window.location.href);
                    var redirect_url = url.searchParams.get('redirect_url');
                    window.location.href = redirect_url ? redirect_url : window.location.href;
                });
            </script>
<?php
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>