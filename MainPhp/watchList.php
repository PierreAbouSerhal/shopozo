<?php
    $title = "Watch List";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/navBar.php");

    if(!$user["userOk"])
    {
        $user["userId"] = -1;
    }

    $sqlFetchWatchProd = 'SELECT products.*,
                                 productPics.picture
                          FROM products
                          JOIN productPics 
                          ON products.id = productPics.productId
                          WHERE products.id IN
                            (
                                SELECT productId 
                                FROM watchlists
                                WHERE watchlists.userId = '.$user["userId"].'
                            )
                            AND productPics.isPrimary = 1';

    $queryFetchWatchProd = mysqli_query($dbConx, $sqlFetchWatchProd);

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
    else if(mysqli_num_rows($queryFetchWatchProd) == 0)
    {
        $msg = "You don't have any items in your watch list. Explore our shop!";

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

        while($resFetchWatchProd = mysqli_fetch_assoc($queryFetchWatchProd))
        {
            $prodId    = $resFetchWatchProd["id"];
            $prodName  = $resFetchWatchProd["name"];
            $prodPic   = $resFetchWatchProd["picture"];
            $prodDescr = $resFetchWatchProd["descr"];
            $prodWatchers = $resFetchWatchProd["totalWatchers"];

            echo '<div class="saved-watch-prod-container">
                    <div class="saved-watch-img-container">
                        <img onclick="prodDetails('.$prodId.');" class="saved-watch-prod-img" src="'.$prodPic.'">
                    </div>
                    <div class="saved-watch-prod-info">
                        <span onclick="prodDetails('.$prodId.');" class="saved-watch-prod-name">'.$prodName.'</span>
                        <span class="watch-total">Total Watchers: '.$prodWatchers.'</span>
                        <span class="saved-watch-prod-descr">'.$prodDescr.'</span>
                    </div>
                    <img class="saved-watch-delete-icon" id="PRD_'.$prodId.'" src="../ShopozoPics/delete.svg">
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
                    url: 'rmvFromWatchList.php',
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
                            alert("Unable to remove product from Watch list");
                        }
                    }
                });
            });
        });
</script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>