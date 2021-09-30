<?php
if (!isset($_SESSION['username'])) {
    gotoPage('home');
} else {
    $sql_order = 'SELECT * FROM `order`';
    $res_order = mysqli_query($connect, $sql_order);
    if ($res_order) {
?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Track Code</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Shipping Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Createtime</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fetch_order = mysqli_fetch_assoc($res_order)) {
                    ?>
                        <tr>
                            <th scope="row"><?= $fetch_order['order_id'] ?></th>
                            <td><?= $fetch_order['u_id'] ?></td>
                            <td><?= $fetch_order['track_code'] ?></td>
                            <td><?= $fetch_order['product_price'] ?></td>
                            <td><?= $fetch_order['shipping_price'] ?></td>
                            <td><?= $fetch_order['status'] ?></td>
                            <td><?= $fetch_order['createtime'] ?></td>
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
