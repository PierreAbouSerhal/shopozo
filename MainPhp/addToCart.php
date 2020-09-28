<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    if(!isset($_GET["prodId"]) || !isset($_GET["qty"]))
    {
        header("Location: index.php");
        exit();
    }

    $msg = "";

    if(!$user["userOk"])
    {
        logout();
        $msg = "Please Sign in with your account to be able to add products to your shopping cart";
    }

    $prodId  = mysqli_real_escape_string($dbConx, $_GET["prodId"]);
    $prodQty = mysqli_real_escape_string($dbConx, $_GET["qty"]);

    $sqlVerifProd = 'SELECT COUNT(*) AS rowNbr FROM products WHERE id = '.$prodId;

    $queryVerifProd = mysqli_query($dbConx, $sqlVerifProd);

    $resVerifProd = mysqli_fetch_assoc($queryVerifProd);

    if($resVerifProd["rowNbr"] == 0)
    {
        header("Location: index.php");
        exit();
    }

    $sqlVerifCart = 'SELECT COUNT(*) AS rowNbr FROM shoppingCarts WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $queryVerifCart = mysqli_query($dbConx, $sqlVerifCart);

    $resVerifCart = mysqli_fetch_assoc($queryVerifCart);

    if($resVerifCart["rowNbr"] == 0)
    {
        $sqlInsert = 'INSERT INTO shoppingCarts (userId, productId, qty)
                        VALUES ('.$user["userId"].', '.$prodId.', '.$prodQty.')';

        $queryInsert = mysqli_query($dbConx, $sqlInsert);
    }

    header("Location: shoppingCart.php");
    exit();
?>