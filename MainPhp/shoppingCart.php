<?php
    $title = "Shopozo shipping cart";
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

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
    if($itemCnt == 0)
    {
        $msg = "You don't have any items in your cart. Let's get shopping!";

        echo '
              <div class="empty-cart">
                    <span class="empty-cart-msg">
                        '.$msg.'
                    </span>
                    <button class="shop-now-btn" onclick-"redirect(\'HOM\');">Start Shopping</button>
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

            echo '<div class="shopping-product-main-container">
                    <div class="shopping-product-img-container">
                        <img class="shopping-product-img" src="'.$resFetchCart["picture"].'">
                    </div>
                    <div class="shopping-product-all-info">
                        <div class="shopping-product-info">
                            <span class="shopping-product-name">'.$prodName.'</span>
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
                            <select class="prod-qty-select" name="prodQty">';
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
                </div>';
        }
        echo '</div>
            
              <div class="right-column">
                <div class="cartssummary">
                <button class="go-to-checkout-btn">Go to checkout</button>
                <span class="item-cnt">
                    item'.$plural.' ('.$itemCnt.')
                </span>
                <div>
                    <span class="subtotal-label sub-font">Subtotal</span>
                    <span id="subtotal" class="subtotal sub-font">'.$subTotal.'</span>
                </div>
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
        
        $(".prod-qty-select").change(function(){
        let qty      = $(this).val();
        let original = $(this).parent().parent().next(".shopping-product-price").children(".prod-original-price").html();
        console.log(original);
        let newTot   = qty * original;

        let orderTotal = $("#subtotal").html();
        orderTotal -= $(this).parent().next(".total-item-price").text().slice(0, -1);
        orderTotal += newTot;

        $(this).parent().next(".total-item-price").text(newTot.toFixed(2));
        $("#subtotal").html(orderTotal.toFixed(2));
    });

</script>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>