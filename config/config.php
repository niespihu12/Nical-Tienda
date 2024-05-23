<?php
    define("CLIENT_ID", "AcZddslwcfus-p3EEF8cTcYj_O8D0zc8J9hVKPalM8mcrfCP32_qObqFP1jiBn9_swPLEMtj4GvYSSDZ");
    define("CURRENCY", "USD");
    define("KEY_TOKEN", "APR.wqc-354*");
    define("MONEDA","COP");
    session_start();


    $num_cart = 0;
    if(isset($_SESSION['carrito']['productos'])){
        $num_cart = count($_SESSION['carrito']['productos']);
    }


?>