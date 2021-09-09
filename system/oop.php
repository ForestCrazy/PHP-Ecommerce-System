<?php
function gotoPage($pageName)
{
?>
    <script>
        window.location.href = '?page=<?= $pageName ?>';
    </script>
<?php
}

function number_abbr($number)
{
    $abbrevs = [12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => ''];

    foreach ($abbrevs as $exponent => $abbrev) {
        if (abs($number) >= pow(10, $exponent)) {
            $display = $number / pow(10, $exponent);
            $decimals = ($exponent >= 3 && round($display) < 100) ? 1 : 0;
            $number = number_format($display, $decimals) . $abbrev;
            break;
        }
    }

    return $number;
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hasOwnStore($u_id, $reqStoreId = false)
{
    global $connect;
    $sql_check_hasStore = 'SELECT store_id FROM store WHERE u_id = "' . $u_id . '"';
    $res_check_store = mysqli_query($connect, $sql_check_hasStore);
    if ($res_check_store) {
        if (mysqli_num_rows($res_check_store) == 1) {
            return $reqStoreId ? mysqli_fetch_assoc($res_check_store)['store_id'] : true;
        } else {
            return false;
        }
    }
}

function isProductOfStore($p_id, $store_id)
{
    global $connect;
    $sql_check_product = 'SELECT product_id FROM product WHERE product_id = "' . mysqli_real_escape_string($connect, $p_id) . '" AND store_id = "' . mysqli_real_escape_string($connect, $store_id) . '"';
    $res_check_product = mysqli_query($connect, $sql_check_product);
    if ($res_check_product) {
        if (mysqli_num_rows($res_check_product) == 1) {
            return true;
        } else {
            return false;
        }
    }
}
