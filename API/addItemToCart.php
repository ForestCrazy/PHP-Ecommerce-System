<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_POST['submit_addToCart'])) {
    $sql_addToCart = 'INSERT INTO cart (u_id, product_id, quantity, shipping_id) VALUES ("' . $_SESSION['u_id'] . '", "' . mysqli_real_escape_string($connect, $_GET['p_id']) . '", "' . $_POST['quantity'] . '", "' . mysqli_real_escape_string($connect, $_POST['shipping_id']) . '")';
    $res_addToCart = mysqli_query($connect, $sql_addTOCart);
    if ($res_addToCart) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
} else {
    echo json_encode(array('success' => false));
}
