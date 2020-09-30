<?php
    $title = "Home page";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/navBar.php");

    $sqlRecent = 'SELECT hist.*,
                         prodPic.picture, 
                         prod.price, 
                         prod.name AS prodName
                  FROM 
                    ( 
                        history AS hist
                        JOIN productPics AS prodPic
                        ON hist.productId = prodPic.productId
                    )
                  JOIN products AS prod
                  ON prod.id = hist.productId
                  WHERE hist.userId = '.$user["userId"].'
                  AND prodPic.isPrimary = 1
                  LIMIT 15';
    
    $queryRecent = mysqli_query($dbConx, $sqlRecent);

    $sqlDaily = 'SELECT prodPic.picture,
                        prod.id AS prodId,
                        prod.price,
                        prod.discount,
                        prod.name AS prodName
                 FROM products AS prod 
                 JOIN productPics AS prodPic
                 ON prod.id = prodPic.productId
                 WHERE prod.modifDate = CURDATE() AND
                       prod.stock > 0
                 LIMIT 15';

    $queryDaily = mysqli_query($dbConx, $sqlDaily);

    $sqlPopular = 'SELECT *
                   FROM subCategories
                   ORDER BY popularityPts
                   DESC';
    
    $queryPopular = mysqli_query($dbConx, $sqlPopular);
        
    
    $sqlIntrested = '   SELECT prodPics.picture,
                                prod.id AS prductId,
                                prod.name AS prodName,
                                prod.popularityPts AS points
                        FROM productPics AS prodPics
                        JOIN 
                            (
                                SELECT products.id, products.name, products.subCategId, subCateg.popularityPts
                                FROM products
                                JOIN subcategories AS subCateg 
                                ON products.subCategId = subCateg.id
                            )AS prod
                        ON prod.id = prodPics.productId
                        WHERE prodPics.isPrimary = 1
                        ORDER BY prod.popularityPts DESC
                        LIMIT 10';
    
    
    
    $queryIntrested = mysqli_query($dbConx, $sqlIntrested);
?>

<div class="intrested-container">
    <div class="intrested-info">
        <div class="intrested-title">
            <a href="#">
                Items you might</br> be intrested in
            </a>
        </div>
        <span class="shop-now-btn">
            <span class="shop-now-text">Shop Now</span>
            <img class="intrested-right-arrow" src="../ShopozoPics/right-arrow.png">
        </span>
    </div>

    <div class="intrested-products">
        <div class="carousel-auto" style="text-align: left;">
        <?php
            if(mysqli_num_rows($queryIntrested) > 0)
            {
                while($resIntrested = mysqli_fetch_assoc($queryIntrested))
                {
                    echo '<div class="carousel-intrested-container">
                                <img class="carousel-intrested-img" src="'.$resIntrested["picture"].'" alt="'.$resIntrested["prodName"].'" onclick="prodDetails('.$resIntrested["prductId"].')">
                          </div>
                        
                    ';
                }
                mysqli_free_result($queryIntrested);
            }
        ?>
        </div>
    </div>

</div>
<!-- POPULAR CATEGORIES -->
<?php
    if(!$user["userOk"])
    {
        if(mysqli_num_rows($queryPopular) > 0)
        {
            echo '<div class="popular-categories-container">
                    <div class="link-title">
                        <a href="#">Explore popular categories</a>
                        <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                    </div>

                    <div class="carousel" style="text-align: left;">';

            while($resPopular = mysqli_fetch_assoc($queryPopular))
            {
                echo '
                        <div class="carousel-item-container carousel-categ">
                            <img class="carousel-item-img" src="'.$resPopular["picture"].'" alt="'.$resPopular["name"].'">
                            <div class="carousel-categ-name-container">
                                <span class="carousel-categ-name">'.$resPopular["name"].'</span>
                            </div>
                        </div>
                ';
            }
            mysqli_free_result($queryPopular);
        }

        echo '</div>
            </div>';
    }
?>
<!-- RECENTLY VIEWED PRODUCTS -->
<div class="recently-viewed-container">
<?php
    if($user["userOk"])
    {
        if(mysqli_num_rows($queryRecent) > 0)
        {
            echo '<div class="link-title">
                    <a href="../MainPhp/history.php">See all your Viewed Items</a>
                    <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                </div>
                <div class="carousel" style="text-align: left;">';

            while($resRecent = mysqli_fetch_assoc($queryRecent))
            {
                echo '
                        <div class="carousel-item-container">
                            <img class="carousel-item-img" style="max-width:200px;max-height:200px" src="'.$resRecent["picture"].'" alt="'.$resRecent["prodName"].'" onclick="prodDetails('.$resRecent["productId"].')">
                            <div class="carousel-price-container">
                                <span class="carousel-item-discount-price">'.$resRecent["price"].'$</span>
                            </div>
                        </div>
                ';
            }
            echo '</div>';
            mysqli_free_result($queryRecent);
        } 
    }
    ?>
</div>
    <!-- DAILY DEALS -->
    <?php
        echo '<div class="daily-deals-container">';
        
        if(mysqli_num_rows($queryDaily) > 0)
        {
            echo '
                <div class="link-title">
                    <a href="#">Daily Deals</a>
                    <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                </div>
                <div class="carousel" style="text-align: left;">';
            while($resDaily = mysqli_fetch_assoc($queryDaily))
            {
                $prodDisc      = $resDaily["discount"];
                $prodPrice     = $resDaily["price"];
                $prodDiscPrice = (empty($prodDisc)) ? $prodPrice : $prodPrice - ($prodPrice * ($prodDisc / 100)) ;

                echo '
                    <div class="carousel-item-container">
                        <img class="carousel-item-img" src="'.$resDaily["picture"].'" alt="'.$resDaily["prodName"].'" onclick="prodDetails('.$resDaily["prodId"].')">
                        <div class="carousel-price-container">
                            <div class="carousel-item-discount-price">'.$prodDiscPrice.'$</div>';
                            if(!empty($prodDisc))
                            {
                                echo '
                                <span class="carousel-item-original-price">'.$prodPrice.'</span>
                                <span class="carousel-item-disc">'.$prodDisc.'% OFF</span>';
                            }

                        echo '
                            </div>
                        </div>
                    </div>
                ';
            }
            mysqli_free_result($queryDaily);
        }
            
        echo '</div>';
    ?>

<script src="../MainJs/carousel.js"></script>

</div>

<?php 
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>
