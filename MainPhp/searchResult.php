<?php

if(!isset($_GET["userSearch"]))
    {
        header("Location: index.php");
        exit();
    }

    $title = $_GET["userSearch"]." | Shopozo";

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $userSearch = (isset($_GET["userSearch"])) ? mysqli_real_escape_string($dbConx, $_GET["userSearch"]) : "";
    $subCategId = (isset($_GET["subCategId"])) ? mysqli_real_escape_string($dbConx, $_GET["subCategId"]) : "";

        $sqlFetchProds = 'SELECT products.*,
                                 brands.name AS brandName
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

    $sqlFetchSpecs = 'SELECT specs.*
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

    echo $sqlFetchProds;
        
    $queryFetchSpecs = mysqli_query($dbConx, $sqlFetchSpecs);
    
    if(isset($_POST["fops"]))
    {
        $specNbr = 0;
        while($resFetchSpecs = mysqli_fetch_assoc($queryFetchSpecs))
        {
            ${"FOP$specNbr"} = (isset($_POST["fop_".$resFetchSpecs["name"]]) && !empty($_POST["fop_".$resFetchSpecs["name"]])) ?
                                 'WHERE productSpecs.specId = '.$resFetchSpecs["id"].' AND productSpecs.value = '.mysqli_fetch_assoc($dbConx, $_POST["fop_".$resFetchSpecs["name"]])  :
                                 'true';
            $specNbr += 1;
            echo ${"FOP$specNbr"};
        }
    }

    $sqlFetchProdFOP = 'SELECT products.* 
                        FROM products 
                        JOIN productSpecs 
                        ON products.id = productSpecs.productId
                        WHERE productsSpecs.';

    ?>
<div class="filter-and-products-container">
    <form class="filter-container">
        <h2 class="filter-tiltle">Filter Options</h2>
        <?php
            mysqli_data_seek($queryFetchSpecs, 0);

            while($resFetchSpecs = mysqli_fetch_assoc($queryFetchSpecs))
            {
                echo '<div class="filter-option">
                        <p class="filter-name">'.$resFetchSpecs["name"].'</p>
                        <input type="text" name="fop_'.$resFetchSpecs["name"].'">
                      </div>';
            }
        ?>
        <input class="filter-btn " type="submit" name="fops" value="Filter">
        
    </form>
</div>