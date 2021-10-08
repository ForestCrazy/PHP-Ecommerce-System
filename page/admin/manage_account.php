<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $sql_user = 'SELECT * FROM user';
    $res_user = mysqli_query($connect, $sql_user);
    if ($res_user) {
?>
        <div class="table-responsive col-top">
            <table class="table display" id="table-admin">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Createtime</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fetch_user = mysqli_fetch_assoc($res_user)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $fetch_user['u_id'] ?></th>
                            <td><?= $fetch_user['username'] ?></td>
                            <td><?= $fetch_user['email'] ?></td>
                            <td><?= $fetch_user['createtime'] ?></td>
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
