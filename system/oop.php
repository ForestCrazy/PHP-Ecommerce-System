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

function hasOwnStore($u_id)
{
    global $connect;
    $sql_check_hasStore = 'SELECT store_name FROM store WHERE u_id = "' . $u_id . '"';
    $res_check_store = mysqli_query($connect, $sql_check_hasStore);
    if ($res_check_store) {
        if (mysqli_num_rows($res_check_store) == 1) {
            return true;
        } else {
            return false;
        }
    }
}
