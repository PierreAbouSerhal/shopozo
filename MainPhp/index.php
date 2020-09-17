<?php
    $title = "Home page";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/navBar.php");

    $sqlRecent = 'SELECT hist.*, prodPic.picture, COUNT(*) AS rowNbr
                  FROM history AS hist
                  JOIN productPics AS prodPic
                  ON hist.productId = prodPic.productId 
                  WHERE hist.userId = '.$user["userId"].'
                  AND prodPic.isPrimary = 1
                  LIMIT 10';

    $queryRecent = mysqli_query($dbConx, $sqlRecent);

?>

<div class="intrested-container">
    <div class="intrested-info">

        <p class="intrested-title">
            Items you might</br> be intrested in
        </p>
        <span class="shop-now-btn">
            <span class="shop-now-text">Shop Now</span>
            <img class="intrested-right-arrow" src="../ShopozoPics/right-arrow.png">
        </span>
    </div>

    <div class="intrested-images">

    </div>

    <?php
        if(mysqli_num_rows($queryRecent) > 0)
        {
            echo '<div class="recently-viewed-container">';
            
            while($resRecent = mysqli_fetch_assoc($queryRecent))
            {
                echo '';
            }

            echo '</div>';
        }
    ?>
</div>

<!-- CLOSING THE CONTAINER, BODY, AND HTML TAGS --> 
</div>
</body>
</html>