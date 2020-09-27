<?php

if(!isset($_GET["prodId"]) || empty($_GET["prodId"]))
    {
        header("Location: index.php");
        exit(); 
    }
    
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");

    $prodId = mysqli_real_escape_string($dbConx, $_GET["prodId"]);

    $sqlFetchProd = 'SELECT *, COUNT(*) AS rowNbr FROM products WHERE id = '.$prodId.' AND stock <> 0';

    $queryFetchProd = mysqli_query($dbConx, $sqlFetchProd);

    $resFetchProd = mysqli_fetch_assoc($queryFetchProd);

    mysqli_free_result($queryFetchProd);

    if($resFetchProd["rowNbr"] == 0)
    {
        header("Location: index.php");
        exit();
    }

    $prodName      = $resFetchProd["name"];
    $prodDisc      = $resFetchProd["discount"];
    $prodPrice     = $resFetchProd["price"];
    $prodDiscPrice = $prodPrice - ($prodPrice * ($prodDisc / 100));
    $prodStock     = $resFetchProd["stock"];
    $prodDescr     = $resFetchProd["descr"];
    $prodCond      = $resFetchProd["prodCond"];
    $prodTotSold   = $resFetchProd["totalSold"];
    $prodTotWatch  = $resFetchProd["totalWatchers"];
    $prodShipTime  = $resFetchProd["shipTime"];
    $prodSubCateg  = $resFetchProd["subCategId"];

    //DELIVERY DATES -- ALL PRODUCTS HAVE A MARGIN OF 4 DAYS FOR DELIVERY
    $currentDate   = date('Y-m-d');
    $deliveryDate1 = date('Y-m-d', strtotime($currentDate. ' + '.$prodShipTime.' days'));
    $deliveryDate2 = date('Y-m-d', strtotime($currentDate. ' + '.($prodShipTime + 4).' days'));

    $prodShipD1     = date("d", strtotime($deliveryDate1));
    $prodShipDName1 = date('D', strtotime($deliveryDate1));
    $prodShipM1     = date('M', strtotime($deliveryDate1));
    
    $prodShipD2     = date("d", strtotime($deliveryDate2));
    $prodShipDName2 = date('D', strtotime($deliveryDate2));
    $prodShipM2     = date('M', strtotime($deliveryDate2));

    $deliveryDate1 = $prodShipDName1.". ".$prodShipM1.". ".$prodShipD1;
    $deliveryDate2 = $prodShipDName2.". ".$prodShipM2.". ".$prodShipD2;

    $sqlFetchProdPics = 'SELECT productpics.picture FROM productpics WHERE productId = '.$prodId;

    $queryFetchPics = mysqli_query($dbConx, $sqlFetchProdPics);

    $sqlFetchProdSpecs = 'SELECT productSpecs.value,
                                 specs.name
                          FROM productSpecs 
                          JOIN specs ON productSpecs.specId = specs.id
                          WHERE productSpecs.productId = '.$prodId;

    $queryFetchProdSpecs = mysqli_query($dbConx, $sqlFetchProdSpecs);

    $sqlRelatedItems = 'SELECT * FROM products
                        JOIN productPics
                        ON products.id = productPics.productId
                        WHERE products.subCategId = '.$prodSubCateg.
                        ' AND productPics.isPrimary = 1
                        AND products.id <> '.$prodId;
    
    $queryFetchRelatedItems = mysqli_query($dbConx, $sqlRelatedItems);

    $title = $prodName." | Shopozo";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $userSaved = $userWList = array();

    if($user["userOk"])
    {
        $sqlFetchSaved = 'SELECT * FROM savedProducts WHERE userId = '.$user["userId"];

        $queryFetchSaved = mysqli_query($dbConx, $sqlFetchSaved);

        while($resFetchSaved = mysqli_fetch_assoc($queryFetchSaved))
        {
            $userSaved[] = $resFetchSaved["productId"];
        }
        mysqli_free_result($queryFetchSaved);

        $sqlFetchWatchList = 'SELECT * FROM watchLists WHERE userId = '.$user["userId"];

        $queryFetchWatchList = mysqli_query($dbConx, $sqlFetchWatchList);

        while($resFetchWatchList = mysqli_fetch_assoc($queryFetchWatchList))
        {
            $userWList[] = $resFetchWatchList["productId"];
        }
        mysqli_free_result($queryFetchWatchList);
    }

?>

<div class="main-product-container">
    <div class="product-pics">
        <div class="carousel-product-auto" style="text-align: left;">
            <?php
                while($resFetchPics = mysqli_fetch_assoc($queryFetchPics))
                {
                    echo '
                            <div class="carousel-product-img-container">
                                <img class="carousel-products-img" src="'.$resFetchPics["picture"].'" alt="'.$prodName.'">
                            </div>
                    ';
                }
                mysqli_free_result($queryFetchPics);
            ?>
        </div>
    </div>
    <div class="product-info-container">
        <h1 class="detail-product-name"><?php echo $prodName;?></h1>
        <?php
            if($prodTotWatch > 0)
            {
                echo '<p class="detail-product-watchers margin-top">Watchers: '.$prodTotWatch.'</p>';
            }
        ?>
    
        <div class="product-cond-and-stock">
            <span class="detail-product-condition-label row-label">Condition:</span>
            <span class="detail-product-condition"><?php echo $prodCond?></span>
            <span class="detail-product-discount-price-label row-label">Price:</span>
            <span class="detail-product-discount-price"><?php echo $prodDiscPrice?>$</span>
            <?php
                if($prodPrice != $prodDiscPrice)
                {
                    echo '
                            <span class="detail-product-old-price-label row-label">Was: </span>
                            <span class="detail-product-old-price-container">
                                <span class="detail-product-old-price">'.$prodPrice.'$</span>
                                <span class="detail-product-discount" style="text-decoration:none">'.$prodDisc.'% off</span>
                            </span>';
                }
            ?>
            <span class="detail-product-stock-label row-label">Stock:</span>
            <span class="detail-product-stock"><?php echo $prodStock;?> Left</span>
            <span class="detail-product-quantity-label row-label">Quantity:</span>
            <?php
                if($prodStock > 1)
                {
                        echo '
                              <span class="detail-product-quantity">
                                <select id="prodQty">';
                                for($i = 1; $i <= $prodStock; $i++)
                                {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                        echo    '</select>
                              </span>';
                }
                else
                {
                    echo '<span class="detail-product-quantity">1</span>';
                }
            ?>
            <button class="buy-now-btn product-detail-btn" onclick="checkOutOneProd(<?php echo $prodId?>)">By it Now</button>
            <button class="Add-to-cart-btn product-detail-btn">Add To Cart</button>

            <?php
                if($user["userOk"])
                {
                    echo '<span class="detail-product-list-btns">';

                    if(in_array($prodId, $userSaved))
                    {
                        echo '<img class="product-detail-heart-circle-icon" id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/heart-circle-filled.svg">';
                    }
                    else
                    {
                        echo '<img class="product-detail-heart-circle-icon" id="PRD_ADD_'.$prodId.'" src="../ShopozoPics/heart-circle.svg">';
                    }
                    if(in_array($prodId, $userWList))
                    {
                        echo '<img class="product-detail-watch-icon" id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/pressed-watch-eye.svg">';
                    }
                    else
                    {
                        echo '<img class="product-detail-watch-icon" id="PRD_ADD_'.$prodId.'" src="../ShopozoPics/watch-eye.svg">';
                    }
                    
                    echo '</span>';
                }
            ?>

        </div>
            <div class="border-bottom-empty-div"></div>
        <div class="product-ship-and-descr">
            <span class="detail-product-delivery-label row-label">Delivery:</span>
            <span class="detail-product-delivery">Estimated between <strong> <?php echo $deliveryDate1." and ".$deliveryDate2?></strong></span>
            <span class="detail-product-payment-label row-label">Payments:</span>
            <span class="detail-product-payment"><strong>Cash on delevery</strong></span>
            <span class="detail-product-descr-label row-label">Description:</span>
            <span class="detail-product-descr"><?php echo $prodDescr?></span>

        </div>
    </div>
</div>

<div class="item-specs-main-container">
    <div class="specs-and-related-items-container">
        <div class="item-specs-container">
            <h2 class="item-specs-header">Item Specs</h2>
            <div class="item-specs">
                <?php
                    $rowCnt = 1;
                    while($resFetchProdSpecs = mysqli_fetch_assoc($queryFetchProdSpecs))
                    {
                        echo '<span style="grid-column-start: 1; grid-column-end: 1;">
                                <strong>'.$resFetchProdSpecs["name"].':</strong>
                            </span>
                            <span style="grid-column-start: 2; grid-column-end: 2; grid-row-start: '.$rowCnt.'; grid-row-end: '.$rowCnt.';">
                                '.$resFetchProdSpecs["value"].'
                            </span>
                            ';
                        $rowCnt += 1;
                    }
                ?>
            </div>
        </div>
        
        <div class="related-items-container">
            <div class="related-items-title-container">
                <h2 class="realated-items-title">Related Items</h2>
            </div>
            <div class="related-items-carousel" style="text-align: left;">
                <?php
                    while($resFetchRelatedItems = mysqli_fetch_assoc($queryFetchRelatedItems))
                    {
                        $prodDisc      = $resFetchRelatedItems["discount"];
                        $prodPrice     = $resFetchRelatedItems["price"];
                        $prodDiscPrice = (empty($prodDisc)) ? $prodPrice : $prodPrice - ($prodPrice * ($prodDisc / 100));
                        $prodPicture   = $resFetchRelatedItems["picture"];
                        $prodName      = $resFetchRelatedItems["name"];

                        echo '
                            <div class="carousel-related-item-container" onclick="prodDetails('.$resFetchRelatedItems["id"].')">
                                <img class="carousel-related-item-img" src="'.$prodPicture.'" alt="'.$prodName.'">
                                <div class="carousel-price-container">
                                    <div class="carousel-item-discount-price">'.$prodDiscPrice.'$</div>';
                                    if(!empty($prodDisc))
                                    {
                                        echo '
                                        <span class="carousel-item-disc">Was: </span>
                                        <span class="carousel-item-original-price">'.$prodPrice.'$</span>
                                        <span class="carousel-item-disc">'.$prodDisc.'% OFF</span>';
                                    }

                                echo '
                                </div>
                            </div>
                        ';
                    }
                    ?>                                    
            </div>
        </div>
    </div>
</div>

<script src="../MainJs/carousel.js"></script>
<script>
        //ADD/REMOVE PRODUCT FROM SAVED 
        $(document).ready(function()
        {
            //HEART-ICON 
            $('.product-detail-heart-circle-icon').click(function()
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
            $('.product-detail-watch-icon').click(function()
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