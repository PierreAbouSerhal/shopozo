<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !isset($_POST["prodId"]))
    {
        echo 0;
        exit();
    }

    $prodId        = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
    
    $sqlVerif      = 'SELECT COUNT(*) AS rowNbr FROM watchlists WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $queryVerif    = mysqli_query($dbConx, $sqlVerif);

    $sqlDelete     = 'DELETE FROM watchlists WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $sqlUpdateProd = "UPDATE products SET totalWatchers = totalWatchers - 1 WHERE id = ".$prodId;

    $resVerif      = mysqli_fetch_assoc($queryVerif);

    if($resVerif["rowNbr"] == 0)
    {
        echo 0;
        exit();
    }

    $queryUpdateProd = mysqli_query($dbConx, $sqlUpdateProd);

    $queryDelete = mysqli_query($dbConx, $sqlDelete);

    if($queryDelete && $queryUpdateProd)
    {
        echo 1;
        exit();
    }
    else
    {
        echo 0;
        exit();
    }
?>