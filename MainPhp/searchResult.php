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

        $sqlFetchProds = 'SELECT products.*,
                                 brands.name
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

    $sqlFetchSpecs = 'SELECT specs.name
                      FROM specs
                      JOIN subCategSpecs 
                      ON specs.id = subCategSpecs.specId
                      WHERE subCategSpecs.subCategId IN
                            (
                                SELECT DISTINCT(products.subCategId) AS subCategId
                                FROM products
                                JOIN brands 
                                ON brands.id = products.brandId
                                WHERE (products.name LIKE "%'.$userSearch.'%" OR brands.name LIKE "%'.$userSearch.'%")
                            )
                      ORDER BY specs.name';
        
    $queryFetchSpecs = mysqli_query($dbConx, $sqlFetchSpecs);

    ?>
<div class="filter-and-products-container">
    <div class="filter-container">
        <h2 class="filter-tiltle">Filter Options</h2>
        <?php
            while($resFetchSpecs = mysqli_fetch_assoc($queryFetchSpecs))
            {
                echo '<div class="filter-option">
                        <p class="filter-name">'.$resFetchSpecs["name"].'</p>
                        <input type="text">
                      </div>';
            }
        ?>
    </div>
</div>