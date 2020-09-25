<?php

if(!isset($_GET["userSearch"]))
    {
        header("Location: index.php");
        exit();
    }

    $title = $_GET["userSearch"]." | Shopozo";
    $emptyMsg = "Nothing Found. Sorry, but no results were found in our database.";

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $userSearch = (isset($_GET["userSearch"])) ? mysqli_real_escape_string($dbConx, $_GET["userSearch"]) : "";
    $subCategId = (isset($_GET["subCategId"])) ? mysqli_real_escape_string($dbConx, $_GET["subCategId"]) : "";

        $sqlFetchProds = 'SELECT products.*,
                                 productPics.picture
                          FROM (    
                                    SELECT products.*,
                                           brands.name AS brandName
                                    FROM products
                                    JOIN brands 
                                    ON brands.id = products.brandId
                                )AS products
                          JOIN productPics 
                          ON productPics.productId = products.id
                          WHERE (products.name LIKE "%'.$userSearch.'%" OR products.brandName LIKE "%'.$userSearch.'%") AND productPics.isPrimary = 1';
    
    if(!empty($subCategId) && $subCategId > 0)
    {
        $sqlFetchProds .= ' AND products.subCategId = '.$subCategId;
    }

    $sqlFetchProds .= ' ORDER BY products.totalWatchers';

    $queryFetchProds = "";

    $sqlFetchSpecs = 'SELECT DISTINCT(specs.name) AS name,
                             specs.id
                      FROM specs
                      JOIN subCategSpecs 
                      ON specs.id = subCategSpecs.specId
                      WHERE subCategSpecs.subCategId IN
                            (
                                SELECT DISTINCT(products.subCategId) AS subCategId
                                FROM products
                                JOIN brands 
                                ON brands.id = products.brandId
                                WHERE (products.name LIKE "%'.$userSearch.'%" OR brands.name LIKE "%'.$userSearch.'%")';
                     if(!empty($subCategId) && $subCategId > 0)
                     {
                        $sqlFetchSpecs .= ' AND products.subCategId = '.$subCategId;
                     }          
    $sqlFetchSpecs .=') ORDER BY specs.name';
        
    $queryFetchSpecs = mysqli_query($dbConx, $sqlFetchSpecs);

    if(isset($_POST["fops"]))
    {
        $specNbr = $emptyFOP = 0; 
        while($resFetchSpecs = mysqli_fetch_assoc($queryFetchSpecs))
        {
            ${"FOP$specNbr"} = (isset($_POST["fop_".$resFetchSpecs["name"]]) && !empty($_POST["fop_".$resFetchSpecs["name"]])) ?
                                 'productSpecs.specId = '.$resFetchSpecs["id"].' AND productSpecs.value LIKE "%'.mysqli_real_escape_string($dbConx, $_POST["fop_".$resFetchSpecs["name"]]).'%"'  :
                                 'true';
            if(empty($_POST["fop_".$resFetchSpecs["name"]]))
            {
                $emptyFOP += 1;
            }
            $specNbr += 1;
        }

        if($emptyFOP != $specNbr)
        {    
            $sqlFetchProdFOP = 'SELECT products.* 
                                FROM ('.$sqlFetchProds.') AS products 
                                JOIN productSpecs 
                                ON products.id = productSpecs.productId
                                WHERE ';

            for($i = 0; $i < $specNbr; $i++)
            {
                $sqlFetchProdFOP .= ${"FOP$i"};
                if($i != $specNbr -1)
                {
                    $sqlFetchProdFOP .= ' AND ';
                }
            }
            $queryFetchProds = mysqli_query($dbConx, $sqlFetchProdFOP);
        }
        else
        {
            $queryFetchProds = mysqli_query($dbConx, $sqlFetchProds);
        }
    }
    else
    {
        $queryFetchProds = mysqli_query($dbConx, $sqlFetchProds);
    }
    
    $userSaved = $userWList = array();

    if($user["userOk"])
    {
        $sqlFetchSaved = 'SELECT * FROM savedProducts WHERE userId = '.$user["userId"];

        $queryFetchSaved = mysqli_query($dbConx, $sqlFetchSaved);

        while($resFetchSaved = mysqli_fetch_assoc($queryFetchSaved))
        {
            $userSaved[] = $resFetchSaved["productId"];
        }

        $sqlFetchWatchList = 'SELECT * FROM watchLists WHERE userId = '.$user["userId"];

        $queryFetchWatchList = mysqli_query($dbConx, $sqlFetchWatchList);

        while($resFetchWatchList = mysqli_fetch_assoc($queryFetchWatchList))
        {
            $userWList[] = $resFetchWatchList["productId"];
        }
    }

    ?>

<!-- WORK ON THE BELLOW FILTER NAV!! -->

<div id="filterNav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeFilterNav()">&times;</a>
        <span>Filter Options</span>
                    
        <?php
            while($resFetchSubCateg = mysqli_fetch_assoc($queryFetchSubCateg))
            {
                echo '<a href="../MainPhp/categories.php?categId='.$resFetchSubCateg["categoryId"].'&subCategId='.$resFetchSubCateg["id"].'">'.$resFetchSubCateg["name"].'</a>';
            }
        ?>
        <a href="../MainPhp/categories.php?categId=<?php echo $categId?>&subCategId=-1">All Categories</a>
    </div>

<!-- WORK ON THE ABOVE FILTER NAV!! -->
    
<div class="filter-and-products-container">
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"].'?userSearch='.$userSearch.'&subCategId='.$subCategId?>" class="filter-container">
        <h2 class="filter-tiltle">Filter Options</h2>
        <?php
            mysqli_data_seek($queryFetchSpecs, 0);

            while($resFetchSpecs = mysqli_fetch_assoc($queryFetchSpecs))
            {
                if(empty($userSearch))
                {
                    break;
                }

                $value = "";

                if(isset($_POST['fop_'.$resFetchSpecs["name"]]))
                {
                    $value = mysqli_real_escape_string($dbConx, $_POST['fop_'.$resFetchSpecs["name"]]);
                }
                echo '<div class="filter-option">
                        <p class="filter-name">'.$resFetchSpecs["name"].'</p>
                        <input type="text" name="fop_'.$resFetchSpecs["name"].'" value="'.$value.'">
                      </div>';
            }
        ?>
        <input class="filter-btn " type="submit" name="fops" value="Filter">
        
    </form>

    <div class="products-container">
        <div>
            <button class="mobile-filer-btn" onclick="openFilterNav()">Filter Options</button>
            <span class="sub-categ-title">Product List</span>
        </div>

        <?php
                while($resFetchProducts = mysqli_fetch_assoc($queryFetchProds))
                {
                    $prodId        = $resFetchProducts["id"];
                    $watchers      = ($resFetchProducts["totalWatchers"] > 0) ? $resFetchProducts["totalWatchers"]." Watcher" : "";
                    $watchers      = ($watchers > 1) ? $watchers."s" : $watchers;
                    $prodDisc      = $resFetchProducts["discount"];
                    $prodPrice     = $resFetchProducts["price"];
                    $prodDiscPrice = (empty($prodDisc)) ? $prodPrice : $prodPrice - ($prodPrice * ($prodDisc / 100)) ;

                    echo '<div class="product-details-contaier">
                                <div class="product-img-container" onclick="prodDetails('.$prodId.');">
                                <img class="product-img" src="'.$resFetchProducts["picture"].'" alt="'.$resFetchProducts["name"].'">
                            </div>

                            <div class="product-info">
                                <h3 class="product-name" onclick="prodDetails('.$prodId.');">'.$resFetchProducts["name"].'</h3>
                                <div class="product-Condition">'.$resFetchProducts["prodCond"].'</div>
                                <div class="product-discount-price ';
                                if(empty($prodDisc))
                                {
                                    echo "margin-bottom-20";
                                }

                    echo         '">'.$prodDiscPrice.'$</div>';
                                if(!empty($prodDisc))
                                {
                                    echo '<div class="product-old-price-container margin-bottom-20">
                                            <span class="product-Condition">Was:</span>
                                            <span class="product-old-price product-Condition">'.$prodPrice.'$</span>
                                            <span class="product-Condition">'.$prodDisc.'% off</span>
                                          </div>';
                                }
                    echo       '<div class="product-watchers">'.$watchers.'</div>
                            </div>';
                            if($user["userOk"])
                            {
                                if(in_array($prodId, $userSaved))
                                {
                                    echo '<img class="heart-circle-icon" id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/heart-circle-filled.svg">';
                                }
                                else
                                {
                                    echo '<img class="heart-circle-icon" id="PRD_ADD_'.$prodId.'" src="../ShopozoPics/heart-circle.svg">';
                                }
                                if(in_array($prodId, $userWList))
                                {
                                    echo '<img class="watch-icon" id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/pressed-watch-eye.svg">';
                                }
                                else
                                {
                                    echo '<img class="watch-icon" id="PRD_ADD_'.$prodId.'" src="../ShopozoPics/watch-eye.svg">';
                                }
                            }

                    echo '</div>';
                }

                if(mysqli_num_rows($queryFetchProds) == 0)
                {
                    echo '<div class="empty-msg">'.$emptyMsg.'</div>';
                }
            ?>

    </div>
</div>