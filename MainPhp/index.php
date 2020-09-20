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
                   ASC';
    
    $queryPopular = mysqli_query($dbConx, $sqlPopular);
        
    $sqlIntrested = '   SELECT prodPic.picture,
                               prod.id AS intrestedId,
                               prod.name AS prodName
                        FROM productPics AS prodPic,
                             products AS prod
                        WHERE prod.subCategId IN
                        (
                            SELECT prod.subCategId
                            FROM products AS prod
                            JOIN
                            (
                                SELECT allProd.userId, 
                                       allProd.prodId, 
                                       COUNT(prodId) AS total_prod 
                                FROM
                                    (
                                        SELECT h.userId AS userId, 
                                               h.productId AS prodId 
                                        FROM history AS h
                                        UNION ALL
                                        SELECT s.userId AS userId,
                                               s.productId AS prodId 
                                        FROM savedproducts AS s
                                        UNION ALL
                                        SELECT w.userId AS userId,
                                               w.productId AS prodId 
                                        FROM watchlists AS w
                                    ) AS allProd
                                WHERE allProd.userId = '.$user["userId"].'
                                GROUP BY prodId
                                ORDER BY total_prod ASC 
                                LIMIT 3
                            ) AS totProd ON totProd.prodId = prod.id
                        )';
    
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
        <?php
            if(mysqli_num_rows($queryIntrested) > 0)
            {
                while($resIntrested = mysqli_fetch_assoc($queryIntrested))
                {
                    echo '
                        <div class="carousel-auto" style="text-align: left;">
                            <div class="carousel-intrested-container carousel-intrested">
                                <img class="carousel-intrested-img" src="'.$resIntrested["picture"].'" alt="'.$resIntrested["prodName"].'">
                            </div>
                        </div>
                    ';
                }
                mysqli_free_result($queryIntrested);
            }
                
        ?>
    </div>

</div>
<!-- POPULAR CATEGORIES -->
<?php
    if(!$user["userOk"])
    {
        echo '<div class="popular-categories-container">';

        if(mysqli_num_rows($queryPopular) > 0)
        {
            while($resPopular = mysqli_fetch_assoc($queryPopular))
            {
                echo '
                    <div class="link-title">
                        <a href="#">Explore popular categories</a>
                        <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                    </div>
                    <div class="carousel" style="text-align: left;">
                        <div class="carousel-item-container carousel-categ">
                            <img class="carousel-item-img" src="'.$resPopular["picture"].'" alt="'.$resPopular["name"].'">
                            <div class="carousel-categ-name-container">
                                <span class="carousel-categ-name">'.$resPopular["name"].'</span>
                            </div>
                        </div>
                    </div>
                ';
            }
            mysqli_free_result($queryPopular);
        }

        echo '</div>';
    }
?>
<!-- RECENTLY VIEWED PRODUCTS -->
<?php
        echo '<div class="recently-viewed-container">';
        
        if(mysqli_num_rows($queryRecent) > 0)
        {
            while($resRecent = mysqli_fetch_assoc($queryRecent))
            {
                echo '
                    <div class="link-title">
                        <a href="#">See all your Viewed Items</a>
                        <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                    </div>
                    <div class="carousel" style="text-align: left;">
                        <div class="carousel-item-container">
                            <img class="carousel-item-img" src="'.$resRecent["picture"].'" alt="'.$resRecent["prodName"].'">
                            <div class="carousel-price-container">
                                <span class="carousel-item-discount-price">'.$resRecent["price"].'$</span>
                            </div>
                        </div>
                    </div>
                ';
            }
            mysqli_free_result($queryRecent);
        }

        echo '</div>';   
    ?>
    <!-- DAILY DEALS -->
    <?php
        echo '<div class="daily-deals-container">';
        
        if(mysqli_num_rows($queryDaily) > 0)
        {
            while($resDaily = mysqli_fetch_assoc($queryDaily))
            {
                $prodDisc      = $resDaily["discount"];
                $prodPrice     = $resDaily["price"];
                $prodDiscPrice = (empty($prodDisc)) ? $prodPrice : $prodPrice - ($prodPrice * ($prodDisc / 100)) ;

                echo '
                <div class="link-title">
                    <a href="#">Daily Deals</a>
                    <img src="../ShopozoPics/right-arrow.png" style="width: 15px;" alt="Go">
                </div>
                <div class="carousel" style="text-align: left;">
                    <div class="carousel-item-container">
                        <img class="carousel-item-img" src="'.$resDaily["picture"].'" alt="'.$resDaily["prodName"].'">
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
