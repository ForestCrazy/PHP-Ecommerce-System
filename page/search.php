<?php
if (!isset($_GET['query'])) {
    gotoPage('home');
} else {
?>
    <span>ผลการค้นหาสำหรับ : <?= $_GET['query'] ?></span>
    <?php
    $query = explode(" ", $_GET['query']);
    $sql_where = '';
    foreach ($query as $q) {
        $sql_where .= ' OR product_name LIKE "%' . $q . '%"';
    }
    $sql_product = 'SELECT product.product_id, product.product_name, product.product_price, product.store_id, IF(ISNULL(SUM(sub_order.quantity)), 0, SUM(sub_order.quantity)) AS product_order_quantity, product_img.img_url FROM product LEFT JOIN (SELECT product_id, img_url FROM product_img GROUP BY product_id ORDER BY weight ASC) AS product_img ON product.product_id = product_img.product_id LEFT JOIN sub_order ON product.product_id = sub_order.product_id WHERE product.product_name = "' . $_GET['query'] . '"' . $sql_where . ' GROUP BY product.product_id ORDER BY product_order_quantity DESC';
    $res_product = mysqli_query($connect, $sql_product);
    if ($res_product) {
    ?>
        <div class="row row-fix">
            <?php
            while ($fetch_product = mysqli_fetch_assoc($res_product)) {
            ?>
                <div class="col-sm-4 col-lg-3 col-xl-2 col-top">
                    <div class="card">
                        <a class='text-dark' href='?page=product&p_id=<?php echo $fetch_product['product_id']; ?>'>
                            <img loading="lazy" class="card-img-top w-100" src="<?php echo $fetch_product['img_url']; ?>" alt="<?php echo $fetch_product['product_name']; ?>">
                            <div style="padding: 5px 6px 0px 6px;">
                                <p class="card-text product-name-home"><?php echo $fetch_product['product_name']; ?></p>
                                <div class="d-flex justify-content-between bd-highlight mb-3">
                                    <div class="bd-highlight product-price-home">฿ <?php echo $fetch_product['product_price']; ?></div>
                                    <div class="bd-highlight"></div>
                                    <div class="bd-highlight product-sale-home"><small>ขายแล้ว <?php echo $fetch_product['product_order_quantity']; ?> ชิ้น</small></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
<?php
    }
}
