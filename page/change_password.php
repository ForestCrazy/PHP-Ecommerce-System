<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $sql_account = 'SELECT * FROM user WHERE u_id = "' . $_SESSION['u_id'] . '"';
    $res_account = mysqli_query($connect, $sql_account);
    if ($res_account) {
        $fetch_account = mysqli_fetch_assoc($res_account);
    } else {
        gotoPage('home');
    }
?>
    <script>
        updateItemInCart();
    </script>
    <div class="col col-top">

        <!-- Default form login -->
        <form class="text-center border border-light p-5" method='post'>

            <p class="h4">เปลี่ยนรหัสผ่าน</p>

            <div class='form-group'>
                <!-- current Password -->
                <input type="password" name='current_password' class="form-control" placeholder="รหัสผ่านปัจจุบัน" required>
            </div>

            <div class='form-group'>
                <!-- new Password -->
                <input type="password" name='new_password' class="form-control" placeholder="รหัสผ่านใหม่" required>
            </div>
            <div class='form-group'>
                <!-- new Password -->
                <input type="password" name='confirm_new_password' class="form-control" placeholder="ยินยันรหัสผ่านใหม่" required>
            </div>

            <!-- submit Change Password button -->
            <button class="btn btn-green btn-block my-4" type="submit" name='submit_change_password'>เปลี่ยนรหัสผ่าน</button>

        </form>
        <!-- Default form login -->
    </div>
    <?php
    if (isset($_POST['submit_change_password'])) {
        $sql_check_password = 'SELECT password FROM user WHERE u_id = "' . $_SESSION['u_id'] . '"';
        $res_check_password = mysqli_query($connect, $sql_check_password);
        if ($res_check_password) {
            $fetch_check_password = mysqli_fetch_assoc($res_check_password);
            if (hash('sha256', $_POST['current_password']) == $fetch_check_password['password']) {
                if ($_POST['new_password'] == $_POST['confirm_new_password']) {
                    $sql_change_password = 'UPDATE user SET password = "' . hash('sha256', $_POST['new_password']) . '" WHERE u_id = "' . $_SESSION['u_id'] . '"';
                    $res_change_password = mysqli_query($connect, $sql_change_password);
                    if ($res_change_password) {
                        $alrt_icon = 'success';
                        $alrt_title = 'เปลี่ยนรหัสผ่านสำเร็จ';
                    } else {
                        $alrt_icon = 'error';
                        $alrt_title = 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน';
                    }
                } else {
                    $alrt_icon = 'error';
                    $alrt_title = 'รหัสผ่านใหม่ไม่ตรงกัน';
                }
            } else {
                $alrt_icon = 'error';
                $alrt_title = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
            }
        } else {
            $alrt_icon = 'error';
            $alrt_title = 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน';
        }
    ?>
        <script>
            Swal.fire(
                '<?php echo $alrt_title ?>',
                '',
                '<?php echo $alrt_icon ?>',
            ).then((value) => {
                window.location.href = window.location.href;
            });
        </script>
<?php
    }
}
