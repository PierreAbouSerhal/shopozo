<?php

if(!isset($_GET["prodId"]) || empty($_GET["prodId"]))
    {
        header("Location: index.php");
        exit(); 
    }
    
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");

    $prodId = mysqli_real_escape_string($dbConx, $_GET["prodId"]);

    $sqlFetchProd = 'SELECT *, COUNT(*) AS rowNbr FROM products WHERE id = '.$prodId;

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
            <span class="detail-product-condition-label">Condition:</span>
            <span class="detail-product-condition"><?php echo $prodCond?></span>
            <span class="detail-product-discount-price-label">Price:</span>
            <span class="detail-product-discount-price"><?php echo $prodDiscPrice?>$</span>
            <?php
                if($prodPrice != $prodDiscPrice)
                {
                    echo '
                            <span class="detail-product-old-price-label">Was: </span>
                            <span class="detail-product-old-price">'.$prodPrice.'$
                                <span class="detail-product-discount">'.$prodDisc.'% off</span>
                            </span>';
                }
            ?>
            <span class="detail-product-stock-label">Stock:</span>
            <span class="detail-product-stock"><?php echo $prodStock;?> Left</span>

            <button class="buy-now-btn product-detail-btn">By it Now</button>
            <button class="Add-to-cart-btn product-detail-btn">Add To Cart</button>

            <?php
                if($user["userOk"])
                {
                    echo '<span class="detail-product-list-btns">';

                    if(in_array($prodId, $userSaved))
                    {
                        echo '<img class="heart-circle-icon" id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/heart-circle-filled.svg">';
                    }
                    else
                    {
                        echo '<img id="PRD_ADD_'.$prodId.'" src="../ShopozoPics/heart-circle.svg">';
                    }
                    if(in_array($prodId, $userWList))
                    {
                        echo '<img id="PRD_RMV_'.$prodId.'" src="../ShopozoPics/pressed-watch-eye.svg">';
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
            <span class="detail-product-delivery-label">Delivery:</span>
            <span class="detail-product-delivery">Estimated between <strong> <?php echo $deliveryDate1." and ".$deliveryDate2?></strong></span>
            <span class="detail-product-payment-label">Payments:</span>
            <span class="detail-product-payment"><strong>Cash on delevery</strong></span>
            <span class="detail-product-descr-label">Description</span>
            <span class="detail-product-descr"><?php echo $prodDescr?></span>

        </div>
    </div>
</div>

<div class="item-specs-container">
    <h2 class="item-specs-header">Item Specs</h2>
    <div class="item-specs">
        <?php
            $rowCnt = 1;
            while($resFetchProdSpecs = mysqli_fetch_assoc($queryFetchProdSpecs))
            {
                echo '<span style="grid-column-start: 1; grid-column-end: 1;">
                        '.$resFetchProdSpecs["name"].':
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

<script src="../MainJs/carousel.js"></script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>