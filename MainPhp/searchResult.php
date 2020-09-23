<?php

if(!isset($_POST["userSearch"]))
    {
        header("Location: index.php");
        exit();
    }

    $title = $_POST["userSearch"]." | Shopozo";

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $userSearch = mysqli_real_escape_string($dbConx, $_POST["userSearch"]);
    $subCategId = (isset($_POST["subCategId"])) ? mysqli_real_escape_string($dbConx, $_POST["subCategId"]) : "";

        $sqlFetchProds = 'SELECT *
                          FROM products
                          JOIN brands 
                          ON brands.id = products.brandId
                          WHERE (products.name LIKE "%'.$userSearch.'%" OR brands.name LIKE "%'.$userSearch.'%")';
    
    if(!empty($subCategId))
    {
        $sqlFetchProds .= ' AND products.subCategId = '.$subCategId;

    }

    $sqlFetchProds .= ' ORDER BY products.totalWatchers';

    $queryFetchProds = mysqli_query($dbConx, $sqlFetchProds);

    ?>