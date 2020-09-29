<?php
    $title = "Shopozo shopping cart";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    if(!$user["userOk"])
    {
        $user["userId"] = -1;
    }

    $sqlFetchCart = 'SELECT products.*,
                            products.picture,
                            shoppingCarts.qty
                     FROM (
                            SELECT products.*,
                                   productPics.picture
                            FROM products 
                            JOIN productPics 
                            ON products.id = productPics.productId
                            WHERE productPics.isPrimary = 1
                          )AS products
                     JOIN shoppingCarts
                     ON products.id = shoppingCarts.productId';

    $queryFetchCart = mysqli_query($dbConx, $sqlFetchCart);
    
    $sqlItemCnt = 'SELECT SUM(qty) AS sumQty 
                   FROM shoppingCarts
                   WHERE userId = '.$user["userId"];

    $queryItemCnt = mysqli_query($dbConx, $sqlItemCnt);

    $resItemCnt = mysqli_fetch_assoc($queryItemCnt);

    $itemCnt = $resItemCnt["sumQty"];
    
    $plural = ($itemCnt == 1) ? "" : "s";
?>

<div class="page-header">
    <h2 class="page-header-title">Shopping cart <?php 
                        if($itemCnt > 0)
                        {
                            echo $itemCnt." (item".$plural.")";
                        }
                        ?>
    </h2>
</div>

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
    else if($itemCnt == 0)
    {
        $msg = "You don't have any items in your cart. Let's get shopping!";

        echo '
              <div class="empty-cart">
                    <span class="empty-cart-msg">
                        '.$msg.'
                    </span>
                    <button class="shop-now-btn" onclick="redirect(\'HOM\');">Start Shopping</button>
              </div>';
    }
    else
    {   
        $subTotal = 0;

        echo '<div class="shopping-cart-main-container">
                <div class="left-column">';

        while($resFetchCart = mysqli_fetch_assoc($queryFetchCart))
        {
            $prodId    = $resFetchCart["id"];
            $prodName  = $resFetchCart["name"];
            $prodqty   = $resFetchCart["qty"];
            $prodCond  = $resFetchCart["prodCond"];
            $prodDescr = $resFetchCart["descr"];
            $prodPrice = $resFetchCart["price"] - ($resFetchCart["price"] * ($resFetchCart["discount"] / 100));
            $prodtotal = $prodPrice * $prodqty;
            $prodStock = $resFetchCart["stock"];
            $subTotal  += $prodtotal;

            echo '
            <span class="loading-wrapper"> 
            <img class="loading-anim" id="img_'.$prodId.'" src="../ShopozoPics/loading-anim.gif">
                <div class="shopping-product-main-container" id="product_'.$prodId.'">
                        <div class="shopping-product-img-container">
                            <img class="shopping-product-img" src="'.$resFetchCart["picture"].'" onclick="prodDetails('.$prodId.')">
                        </div>
                        <div class="shopping-product-all-info">
                            <div class="shopping-product-info">
                                <span class="shopping-product-name" onclick="prodDetails('.$prodId.')">'.$prodName.'</span>
                                <span class="shopping-product-cond">'.$prodCond.'</span>
                                <span class="shopping-product-descr">'.$prodDescr.'</span>
                            </div>
                            <div class="shopping-product-qty">';

                            if($prodStock == 1)
                            {
                                echo '<span class="prod-qty">Qty: '.$prodqty.'</span>';
                            }
                            else
                            {
                                echo '<span class="prod-qty">Qty: 
                                <select class="prod-qty-select" id="prod-qty-select_'.$prodId.'" name="prodQty">';
                                for($i = 1; $i <= $prodStock ; $i++)
                                {   
                                    $selected = ($i == $prodqty) ? "selected" : "";
                                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                }
                                echo '</select>
                                </span>';
                            }
                    echo '  </div>
                            <div class="shopping-product-price">
                                <span class="prod-price">'.$prodtotal.'$</span>
                                <span style="display:none;" class="prod-original-price">'.$prodPrice.'</span>
                            </div>
                        </div>
                        <img id="PRD_'.$prodId.'" class="delete-icon" src="../ShopozoPics/delete.svg">
                    </div>
                </span>';
        }
        echo '</div>

              <div class="right-column">
                <div class="cartssummary">
                <button class="go-to-checkout-btn" onclick="redirect(\'CHK\')">Go to checkout</button>
                <span class="item-cnt">
                    item'.$plural.' ('.$itemCnt.')
                </span>
                <div>
                    <span class="subtotal-label sub-font">Subtotal</span>
                    <span id="subtotal" class="subtotal sub-font">'.$subTotal.'</span>
                </div>
              </div>
              <div style="margin-top:10px;text-align:left;padding-left:15px">
                <a style="color:#6EBE47;margin-top: 10px;font-size:0.8rem" href="../Mainphp/orders.php?orderId=-1">View orders</a>
              </div>
            </div>
        </div>';
    }
?>

<script>
    //REMOVE PRODUCTS FROM SHOPPING CART
    $(document).ready(function()
    {
        $('.delete-icon').click(function()
        {
            let el = this;
            let id = this.id;
            let splitid = id.split("_");

            //IDS
            let prodId = splitid[1];
            let userId = <?php echo $user["userId"];?>;
            $(el).attr("src", "../ShopozoPics/loading-anim.gif");

            //AJAX REQUEST
            $.ajax(
            {
                url: 'rmvFromCart.php',
                type: 'POST',
                data: { prodId: prodId, userId: userId },
                success: function(response)
                {
                    if(response == 1)
                    {
                        $(el).parent().hide('slow', function(){ $(el).parent().remove();});
                    }
                    else
                    {
                        alert("Unable to remove product from shopping cart");
                    }
                }
            });
        });
        
        $(".prod-qty-select").change(function()
        {   
            let el         = this;
            let qty        = $(el).val();
            let original   = $(el).parent().parent().next(".shopping-product-price").children(".prod-original-price").html();
            
            let newTot     = qty * original;

            let orderTotal = $("#subtotal").html();
            orderTotal    -= $(el).parent().parent().next(".shopping-product-price").children(".prod-price").html().slice(0, -1);
            orderTotal    += newTot;

            let id = this.id;
            let splitid = id.split("_");

            //IDS
            let prodId = splitid[1];
            $("#product_"+prodId).addClass("loading"); 
            $("#img_"+prodId).css("display", "block");

            //AJAX REQUEST
            $.ajax(
            {
                url: 'updateCart.php',
                type: 'POST',
                data: { prodId: prodId, qty: qty},
                success: function(response)
                {
                    if(response == 1)
                    {

                        $(el).parent().parent().next(".shopping-product-price").children(".prod-price").html(newTot.toFixed(2)+"$");
                        $("#subtotal").html(orderTotal.toFixed(2));

                        $("#product_"+prodId).removeClass("loading"); 
                        $("#img_"+prodId).css("display", "none");
                    }
                    else
                    {
                        alert("Unable to update your shopping cart");
                    }
                }
            });
        });
    });

</script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>