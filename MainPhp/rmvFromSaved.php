<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !isset($_POST["prodId"]))
    {
        echo 0;
        exit();
    }

    $prodId = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
    
    $sqlVerif = 'SELECT COUNT(*) AS rowNbr FROM savedProducts WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $queryVerif = mysqli_query($dbConx, $sqlVerif);

    $resVerif = mysqli_fetch_assoc($queryVerif);

    if($resVerif["rowNbr"] == 0)
    {
        echo 0;
        exit();
    }

    $sqlDelete = 'DELETE FROM savedProducts WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

    $queryDelete = mysqli_query($dbConx, $sqlDelete);

    if($queryDelete)
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