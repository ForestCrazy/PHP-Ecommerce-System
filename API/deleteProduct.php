<?php
require('../system/database.php');
require('../system/oop.php');

if (isset($_SESSION['username'])) {
    if (hasOwnStore($_SESSION['u_id'])) {
        if (isProductOfStore($_POST['p_id'], hasOwnStore($_SESSIOn['u_id'], true))) {
            $sql_deleteProduct = '';
        }
    }
}