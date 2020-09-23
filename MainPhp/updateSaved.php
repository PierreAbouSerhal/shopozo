<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");
    
    $prodId = $userId = $sqlAddOrRmv = "";

    if(isset($_POST["prodId"]) && isset($_POST["userId"]))
    {
        $prodId = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
        $userId = mysqli_real_escape_string($dbConx, $_POST["userId"]);
    }
    else
    {
        echo 0;
        exit();
    }

    $sqlFetchProd = 'SELECT COUNT(*) AS rowNbr FROM savedProducts WHERE userId = '.$userId.' AND productId = '.$prodId;

    $queryFetchProd = mysqli_query($dbConx, $sqlFetchProd);
    
    $resFetchProd = mysqli_fetch_assoc($queryFetchProd);

    if($resFetchProd["rowNbr"] == 1)
    {
        $sqlAddOrRmv = 'DELETE FROM savedProducts WHERE userId = '.$userId.' AND productId = '.$prodId;
    }
    else if($resFetchProd["rowNbr"] == 0)
    {
        $sqlAddOrRmv = 'INSERT INTO savedProducts (userId, productId) VALUES ('.$userId.', '.$prodId.')';
    }

    $queryAddOrRmv = mysqli_query($dbConx, $sqlAddOrRmv);

    if($queryAddOrRmv)
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