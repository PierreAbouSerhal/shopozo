<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        header("Location: ../MainPhp/index.php");
        exit();
    }

    $sqlNew = 'SELECT COUNT(*) AS rowNbr
               FROM users WHERE  creationDate = CURDATE()';
        
    $queryNew = mysqli_query($dbConx, $sqlNew);

    $sqlAll = "SELECT COUNT(*) AS rowNbr FROM users";

    $queryAll = mysqli_query($dbConx, $sqlAll);

    $sqlMostWatch = "SELECT products.id,
                            products.name,
                            products.totalWatchers,
                            productPics.picture
                     FROM products
                     JOIN productPics 
                     ON products.id = productPics.productId
                     WHERE productPics.isPrimary = 1
                     ORDER BY totalWatchers DESC 
                     LIMIT 1;";

    $queryMostWatch = mysqli_query($dbConx, $sqlMostWatch);

    $sqlMostViewed = "SELECT history.productId AS id,
                             products.name, 
                             COUNT(history.productId) AS rowNbr,
                             products.picture
                      FROM history
                      JOIN  (
                                SELECT products.id,
                                       products.name,
                                       productPics.picture
                                FROM products
                                JOIN productPics 
                                ON products.id = productPics.productId
                                WHERE productPics.isPrimary = 1
                            ) AS products
                      ON products.id = history.productId
                      GROUP BY history.productId
                      ORDER BY rowNbr DESC 
                      LIMIT 1;";

    $queryMostViewed = mysqli_query($dbConx, $sqlMostViewed);

    $resNew         = mysqli_fetch_assoc($queryNew);
    $resMostWatch   = mysqli_fetch_assoc($queryMostWatch);
    $resAll         = mysqli_fetch_assoc($queryAll);
    $resMostViewed  = mysqli_fetch_assoc($queryMostViewed);

    mysqli_free_result($queryNew);
    mysqli_free_result($queryAll);
    mysqli_free_result($queryMostWatch);
    mysqli_free_result($queryMostViewed);
    
    //FETCHED VARIABLES
    $newUserCnt      = $resNew["rowNbr"];
    $allUsers        = $resAll["rowNbr"];
    
    $mostViewedId    = $resMostViewed["id"];
    $mostWatchedId   = $resMostWatch["id"];

    $mostViewedName  = $resMostViewed["name"];
    $mostWatchedName = $resMostWatch["name"];
    
    $mostViewedPic   = $resMostViewed["picture"];
    $mostWatchedPic  = $resMostWatch["picture"];

    $title = "General Info";

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

      <h3 class="admin-page-header">General Info</h3>

      <div class="main-admin-container">

            <div class="left-column">
                <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
            </div>
                            
            <div class="general-info-container">

                <div class="info-container" style="background-color: rgb(133,187,47);">
                    <p class="info-nbr"><?php echo $newUserCnt?></p>
                    <p class="info">New Registered Users Today</p>
                    <img class="info-img" src="../ShopozoPics/newUser.png">
                </div>

                <div class="info-container" style="background-color: rgb(255,204,3);">
                    <p class="info-nbr"><?php echo $allUsers?></p>
                    <p class="info">Registered Users In Total</p>
                    <img class="info-img" src="../ShopozoPics/multy-user.png">
                </div>

                <div class="info-container" style="background-color: rgb(238,129,0);">
                    <p class="info-txt">Most Viewed Product:</p>
                    <p class="info"><?php echo $mostViewedName?></p>
                    <?php
                        $picPath = (empty($mostViewedPic)) ? "../Shopozo/stars.png" : $mostViewedPic;

                        echo '<img style="cursor:pointer;margin-bottom:20px" class="info-img" src="'.$picPath.'." onclick="prodDetails('.$mostViewedId.')">';
                    ?>
                </div>

                <div class="info-container" style="background-color: rgb(230,62,17);">
                    <p class="info-txt">Most Watched Product:</p>
                    <p class="info"><?php echo $mostWatchedName?></p>
                    <?php
                        $picPath = (empty($mostWatchedPic)) ? "../Shopozo/watch-eye.svg" : $mostWatchedPic;

                        echo '<img style="cursor:pointer;margin-bottom:20px" class="info-img" src="'.$picPath.'." onclick="prodDetails('.$mostWatchedId.')">';
                    ?>
                </div>
               
            </div>
      </div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>