<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");
    
    $prodId = $userId = $sqlAddOrRmv = $sqlUpdatePrd = "";

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

    $sqlFetchProd = 'SELECT COUNT(*) AS rowNbr FROM watchlists WHERE userId = '.$userId.' AND productId = '.$prodId;

    $queryFetchProd = mysqli_query($dbConx, $sqlFetchProd);
    
    $resFetchProd = mysqli_fetch_assoc($queryFetchProd);

    if($resFetchProd["rowNbr"] > 0)
    {
        $sqlAddOrRmv  = 'DELETE FROM watchlists WHERE userId = '.$userId.' AND productId = '.$prodId;
        $sqlUpdatePrd = 'UPDATE products SET totalWatchers = totalWatchers - 1 WHERE id = '.$prodId;
    }
    else if($resFetchProd["rowNbr"] == 0)
    {
        $sqlAddOrRmv  = 'INSERT INTO watchlists (userId, productId) VALUES ('.$userId.', '.$prodId.')';
        $sqlUpdatePrd = 'UPDATE products SET totalWatchers = totalWatchers + 1 WHERE id = '.$prodId;
    }

    $queryAddOrRmv  = mysqli_query($dbConx, $sqlAddOrRmv);
    $queryUpdatePrd = mysqli_query($dbConx, $sqlUpdatePrd);

    if($queryAddOrRmv && $queryUpdatePrd)
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