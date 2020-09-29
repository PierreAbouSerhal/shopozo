<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    $title = "Account Settings";

    if(!$user["userOk"])
    {
        logout();
        header("Location: index/php");
        exit();
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainHeader.php");
?>
<ul class="profile-nav">
    <li class="profile-nav-item-container">
        <a href="profile.php" class="profile-nav-item">Account</a>
    </li>
    <li class="profile-nav-item-container default-profile-item">
        <a aria-current="page" class="profile-nav-item">History</a>
    </li>
</ul>


<?php
    $title = "Saved Products";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/navBar.php");

    if(!$user["userOk"])
    {
        $user["userId"] = -1;
    }

    $sqlFetchHist = 'SELECT products.*,
                            productPics.picture
                    FROM 
                    (
                        SELECT products.*,
                                history.viewDate,
                                history.viewTime
                        FROM products
                        JOIN history
                        ON products.id = history.productId
                        WHERE history.userId = '.$user["userId"].'
                    ) AS products
                    JOIN productPics 
                    ON products.id = productPics.productId
                    WHERE productPics.isPrimary = 1
                    ORDER BY products.viewDate, products.viewTime';

    $queryFetchHist = mysqli_query($dbConx, $sqlFetchHist);

?>

<?php
    if(!$user["userOk"])
    {
        $msg = "You dont have an account";

        echo '
              <div class="empty-cart">
                    <span class="empty-cart-msg">
                        '.$msg.'
                    </span>
                    <button class="shop-now-btn" onclick="redirect(\'SIN\');">Sign in</button>
                    <button style="margin-top:20px" class="shop-now-btn" onclick="redirect(\'REG\');">Register</button>
              </div>';
    }
    else if(mysqli_num_rows($queryFetchHist) == 0)
    {
        $msg = "You don't have any items in your history list. Explore our shop!";

        echo '
              <div class="empty-cart">
                    <span class="empty-cart-msg">
                        '.$msg.'
                    </span>
                    <button class="shop-now-btn" onclick="redirect(\'HOM\');">Start Shopping</button>
              </div>';
    }
    else
    {   echo '<div class="saved-watch-main-container">';

        while($resFetchHist = mysqli_fetch_assoc($queryFetchHist))
        {
            $prodId    = $resFetchHist["id"];
            $prodName  = $resFetchHist["name"];
            $prodPic   = $resFetchHist["picture"];
            $prodDescr = $resFetchHist["descr"];
            $viewDate  = $resFetchHist["viewDate"];
            $viewTime  = $resFetchHist["viewTime"];

            echo '<div class="saved-watch-prod-container">
                    <div class="saved-watch-img-container">
                        <img onclick="prodDetails('.$prodId.');" class="saved-watch-prod-img" src="'.$prodPic.'">
                    </div>
                    <div class="saved-watch-prod-info">
                        <span onclick="prodDetails('.$prodId.');" class="saved-watch-prod-name">'.$prodName.'</span>
                        <span class="saved-watch-prod-descr">'.$prodDescr.'</span>
                        <div class="hist-date-time">
                            <span class="hist-date">Viewed on: '.$viewDate.'</span>
                            <span class="hist-time"> at: '.$viewTime.'</span>
                        </div>
                    </div>
                    <img class="saved-watch-delete-icon" id="HST_'.$prodId.'" src="../ShopozoPics/delete.svg">
                  </div>
            ';
        }

        echo '</div>';
    }
?>

<script>
//REMOVE PRODUCTS FROM SHOPPING CART
    $(document).ready(function()
        {
            $('.saved-watch-delete-icon').click(function()
            {
                let el = this;
                let id = this.id;
                let splitid = id.split("_");

                //IDS
                let prodId = splitid[1];
                $(el).attr("src", "../ShopozoPics/loading-anim.gif");

                //AJAX REQUEST
                $.ajax(
                {
                    url: 'rmvFromHist.php',
                    type: 'POST',
                    data: { prodId: prodId},
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            $(el).parent().hide('slow', function(){ $(el).parent().remove();});
                        }
                        else
                        {
                            alert("Unable to remove product from history list");
                        }
                    }
                });
            });
        });
</script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>




<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>