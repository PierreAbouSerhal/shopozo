<?php
    $categId = "";

    if(!isset($_GET["categId"]) || !is_numeric($_GET["categId"]) || !isset($_GET["subCategId"]) || !is_numeric($_GET["subCategId"]))
    {
        header("Location: index.php");
        exit();
    }

    $title = "CTG_".$_GET["categId"];

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $categId    = mysqli_real_escape_string($dbConx, $_GET["categId"]);
    $subCategId = mysqli_real_escape_string($dbConx, $_GET["subCategId"]);

    $sqlFetchSubCateg = 'SELECT *
                         FROM subCategories
                         WHERE categoryId = '.$categId;

    $queryFetchSubCateg = mysqli_query($dbConx, $sqlFetchSubCateg);

    if(mysqli_num_rows($queryFetchSubCateg) == 0)
    {
        header("Location: index.php");
        exit();
    }

    if($subCategId > 0)
    {
        $sqlVerifSubCateg = 'SELECT COUNT(*) AS rowNbr
                             FROM subCategories
                             WHERE id = '.$subCategId;
        
        $queryVerifSubCateg = mysqli_query($dbConx, $sqlVerifSubCateg);

        $resVerifSubCateg = mysqli_fetch_assoc($queryVerifSubCateg);

        if($resVerifSubCateg["rowNbr"] == 0)
        {
            header("Location: index.php");
            exit();
        }

    }

    $sqlFetchProducts = 'SELECT products.*,
                                productpics.picture
                         FROM (
                                 SELECT products.*, subcategories.categoryId
                                 FROM products JOIN subcategories ON products.subCategId = subcategories.id
                                 WHERE subcategories.categoryId = '.$categId;
    if($subCategId > 0)
    {
        $sqlFetchProducts .= ' AND subcategories.id = '.$subCategId;
    }                             

    $sqlFetchProducts .= ')AS products
                         JOIN productpics ON productpics.productId = products.id
                         WHERE productpics.isPrimary = 1
                         ORDER BY products.totalWatchers DESC';
    
    $queryFetchProducts = mysqli_query($dbConx, $sqlFetchProducts);

    $sqlFetchSaved = $sqlFetchWatchList = $queryFetchSaved = $queryFetchWatchList = "";

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

<div id="subCategNav" class="sidenav mobile">
        <a href="javascript:void(0)" class="closebtn" onclick="closeSubCatgegNav()">&times;</a>
        <span>Cartegories</span>
                    
        <?php
            while($resFetchSubCateg = mysqli_fetch_assoc($queryFetchSubCateg))
            {
                echo '<a href="../MainPhp/categories.php?categId='.$resFetchSubCateg["categoryId"].'&subCategId='.$resFetchSubCateg["id"].'">'.$resFetchSubCateg["name"].'</a>';
            }
        ?>
        <a href="../MainPhp/categories.php?categId=<?php echo $categId?>&subCategId=-1">All Categories</a>
    </div>

<div class="categ-page-header">
    <h1><?php echo $title?></h1>
    <button class="shop-by-categories-btn" onclick="openSubCategNav()">Categories</button>
</div>

<div class="categ-and-products-container">
    <div class="sub-categ-container">
        <span class="sub-categ-title">Shop By Categorie</span>       
        <ul>
            <?php
                mysqli_data_seek($queryFetchSubCateg, 0);

                while($resFetchSubCateg = mysqli_fetch_assoc($queryFetchSubCateg))
                {
                    echo '<li>
                            <a href="../MainPhp/categories.php?categId='.$resFetchSubCateg["categoryId"].'&subCategId='.$resFetchSubCateg["id"].'">'.$resFetchSubCateg["name"].'</a>
                          </li>';
                }
            ?>
                <li>
                    <a href="../MainPhp/categories.php?categId=<?php echo $categId?>&subCategId=-1">All Categories</a>
                </li>
            </ul>
    </div>

    <div class="products-container">
            <span class="sub-categ-title">Product List</span>
            <?php
                while($resFetchProducts = mysqli_fetch_assoc($queryFetchProducts))
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
            ?>
    </div>
</div>

<script>
        //ADD/REMOVE PRODUCT FROM SAVED 
        $(document).ready(function()
        {
            //HEART-ICON 
            $('.heart-circle-icon').click(function()
            {
                let el = this;
                let id = this.id;
                let splitid = id.split("_");

                //IDS
                let status = splitid[1]; 
                let prodId = splitid[2];
                let userId = <?php 
                                if(empty($user["userId"]))
                                {
                                    echo '""';
                                }
                                else
                                {
                                    echo $user["userId"];
                                }
                             ?>;
                $(el).attr("src", "../ShopozoPics/loading-anim.gif");

                //AJAX REQUEST
                $.ajax(
                {
                    url: 'updateSaved.php',
                    type: 'POST',
                    data: { prodId: prodId, userId: userId },
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            //CHANGE ICON
                            if(status == "RMV")
                            {
                                $(el).attr("src", "../ShopozoPics/heart-circle.svg");
                                $(el).attr("id", "PRD_ADD_"+prodId);
                            }
                            else if(status == "ADD")
                            {
                                $(el).attr("src", "../ShopozoPics/heart-circle-filled.svg");
                                $(el).attr("id", "PRD_RMV_"+prodId);
                            }
                        }
                        else
                        {
                            alert("Unable to update saved products");
                        }
                    }
                });
            });
        });

        //ADD/REMOVE PRODUCT FROM WATCHLIST 
        $(document).ready(function()
        {
            //WATCH-ICON
            $('.watch-icon').click(function()
            {
                let el = this;
                let id = this.id;
                let splitid = id.split("_");

                //IDS
                let status = splitid[1]; 
                let prodId = splitid[2];
                let userId = <?php 
                                if(empty($user["userId"]))
                                {
                                    echo '""';
                                }
                                else
                                {
                                    echo $user["userId"];
                                }
                             ?>;

                $(el).attr("src", "../ShopozoPics/loading-anim.gif");

                //AJAX REQUEST
                $.ajax(
                {
                    url: 'updateWishList.php',
                    type: 'POST',
                    data: { prodId: prodId, userId: userId },
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            //CHANGE ICON
                            if(status == "RMV")
                            {
                                $(el).attr("src", "../ShopozoPics/watch-eye.svg");
                                $(el).attr("id", "PRD_ADD_"+prodId);
                            }
                            else if(status == "ADD")
                            {
                                $(el).attr("src", "../ShopozoPics/pressed-watch-eye.svg");
                                $(el).attr("id", "PRD_RMV_"+prodId);
                            }
                        }
                        else
                        {
                            alert("Unable to update wish list");
                        }
                    }
                });
            });
        });
    </script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>