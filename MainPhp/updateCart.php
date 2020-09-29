<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    if(!isset($_POST["prodId"]) || !isset($_POST["qty"]))
    {
        echo 0;
        exit();
    }

    $prodId  = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
    $prodQty = mysqli_real_escape_string($dbConx, $_POST["qty"]);

    $sqlVerifProd = 'SELECT COUNT(*) AS rowNbr FROM products WHERE id = '.$prodId;

    $queryVerifProd = mysqli_query($dbConx, $sqlVerifProd);

    $resVerifProd = mysqli_fetch_assoc($queryVerifProd);

    if($resVerifProd["rowNbr"] == 0)
    {
        echo 0;
        exit();
    }

    $sqlVerifCart = 'SELECT COUNT(*) AS rowNbr FROM shoppingCarts WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $queryVerifCart = mysqli_query($dbConx, $sqlVerifCart);

    $resVerifCart = mysqli_fetch_assoc($queryVerifCart);

    if($resVerifCart["rowNbr"] == 1)
    {
        $sqlUpdate = 'UPDATE shoppingCarts SET qty = '.$prodQty.'
                      WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

        $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

        if($queryUpdate)
        {
            echo 1;
            exit();
        }
    }

    echo 0;
    exit();
?>
