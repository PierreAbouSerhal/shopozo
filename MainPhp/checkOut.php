<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    $isSignin = $canBuy = $oneProd = true;

    if(!$user["userOk"])
    {
        logout();
        $isSignin = false;
    }

    if(!isset($_GET["prodId"]) && !isset($_GET["qty"]))
    {
        $oneProd = false;
    }

    $sqlFetchProdDet = "";

    if($oneProd)
    {
        $prodId  = mysqli_real_escape_string($dbConx, $_GET["prodId"]);
        $prodQty = mysqli_real_escape_string($dbConx, $_GET["qty"]);

        $sqlFetchProdDet = 'SELECT products.*,
                                   COUNT(*) AS rowNbr,
                                   productPics.picture   
                            FROM products
                            JOIN productPics
                            ON products.id = productPics.productId 
                            WHERE id = '.$prodId.' AND stock >= '.$prodQty.' AND productPics.isPrimary = 1';
    }
    else
    {
        $sqlFetchProdDet = 'SELECT products.*, productPics.picture   
                            FROM (  
                                    SELECT products.*, shoppingcarts.qty 
                                    FROM products
                                    JOIN shoppingcarts 
                                    ON products.id = shoppingcarts.productId
                                    WHERE shoppingcarts.userId = '.$user["userId"].'
                                 )AS products
                            JOIN productPics
                            ON products.id = productPics.productId 
                            WHERE stock > 0  AND productPics.isPrimary = 1';
    }
    
    if(empty($userPhone) || empty($userCountry) || empty($userStreet) || empty($userCity) || empty($userProvince) || empty($userPostCode))
    {
        $canBuy = false;
    }

    $queryFetchProdDet = mysqli_query($dbConx, $sqlFetchProdDet);

    if(isset($_POST["confirmPay"]))
    {
        while($resFetchProdDet = mysqli_fetch_assoc($queryFetchProdDet))
        {
            $prodId = $resFetchProdDet["id"];

            $prodQty = mysqli_real_escape_string($dbConx, $_POST["itemQty_".$prodId]);
            
            
        }
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");
?>
<title>Checkout | Shopozo</title>
<link rel="stylesheet" href="../MainCss/mainHeader.css">
<link rel="stylesheet" href="../MainCss/checkOut.css">
<link rel="stylesheet" href="../MainCss/mainFooter.css">

</head>
<body>
<div class="container">
    <div class="title-container">
        <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="shopozo" onclick="redirect('HOM');">
        <h2 class="checkout-header">Checkout</h2>
    </div>

    <form class="main-container" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">

    <?php 
        $itemCnt = $orderTotal = 0;

        if($isSignin && $canBuy)
        {   
            $sqlFetchCountry = 'SELECT name FROM countrys WHERE id = '.$userCountry;

            $queryFetchCountry = mysqli_query($dbConx, $sqlFetchCountry);

            $resFetchCountry = mysqli_fetch_assoc($queryFetchCountry);

            echo '
            <div class="left-column">
                <div class="payment-container">
                    <h3>Payment</h3>
                    <p class="payment-type">Cash On Delevery <strong>(Only Option)</strong></p>
                </div>
                
                <div class="user-info">
                    <h3>Ship To</h3>
                    <p>'.$userName.'</P>
                    <p>'.$resFetchCountry["name"].'</P>
                    <p>'.$userProvince.'</P>
                    <p>'.$userCity.'</P>
                    <p>'.$userStreet.' '.$userPostCode.'</P>
                    <p>'.$userPhone.'</P>
                    <a href="profile.php" class="change-info-link">change</a>
                </div>
                
                <div class="review-item-container">
                    <h3 class="review-item-title">Review item and shipping</h3>';
                    
                    mysqli_data_seek($queryFetchProdDet, 0);

                    while($resFetchProdDet = mysqli_fetch_assoc($queryFetchProdDet))
                    {
                        if($oneProd)
                        {
                            if($resFetchProdDet["rowNbr"] != 1)
                            {
                                header("Location: index.php");
                                exit();
                            }
                        }

                        $prodName      = $resFetchProdDet["name"];
                        $prodCond      = $resFetchProdDet["prodCond"];
                        $prodDisc      = $resFetchProdDet["discount"];
                        $prodPic       = $resFetchProdDet["picture"];
                        $prodPrice     = $resFetchProdDet["price"];
                        $prodStock     = $resFetchProdDet["stock"];
                        $prodShipTime  = $resFetchProdDet["shipTime"];
                        $prodQty       = (isset($_GET["qty"])) ? mysqli_real_escape_string($dbConx, $_GET["qty"]) : $resFetchProdDet["qty"];
                        $prodId        = (isset($_GET["prodId"])) ? mysqli_real_escape_string($dbConx, $_GET["prodId"]) : $resFetchProdDet["id"];
                        $prodDiscPrice = ($prodPrice - ($prodPrice * ($prodDisc  / 100)));
                        $prodLineTot   = $prodDiscPrice * $prodQty;
                        $orderTotal    += $prodLineTot;

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

                        echo '
                        <div class="item-pic-and-info-container">
                            <div class="item-img">
                                <img class="img-primary-pic" src="'.$prodPic.'">
                            </div>
                            <div class="item-info">
                                <p>'.$prodName.'</p>
                                <p>Condition:<span class="item-cond">'.$prodCond.'</span></p>
                                <div class="item-qty-container">
                                    <span>Quantity:</span>
                                    <select class="item-qty" name="itemQty_'.$prodId.'">
                                    ';
                                for($i=1; $i <= $prodStock; $i++)
                                {
                                    $selected = ($i == $prodQty) ? "selected" : "";
                                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                                }           
                    echo '          </select>
                                </div>
                                <p class="total-item-price">'.$prodLineTot.'$</p>
                                <span class="origial-price" style="display:none">'.$prodDiscPrice.'</span>
                                <p><strong>Shipping</strong></p>
                                <p class="delvery-time">Estimated Delevery:</p>
                                <p class="delvery-time">Between '.$deliveryDate1.' and '.$deliveryDate2.'</p>
                            </div>
                        </div>

                        <div class="bottom-border">
                        </div>
                        ';
                    $itemCnt += 1;
                    }

        echo '</div>';
        
        $plural = ($itemCnt > 1) ? "s" : "";

        echo '</div>
            <div class="right-column">
                <p>Subtotal ('.$itemCnt.' item'.$plural.')</p>
                <div class="bottom-border"></div>
                <p class="order-total-container">
                    <span>
                        <strong>Order Total:</strong>
                    </span>
                    <span id="orderTotal" style="float:right;">
                        '.$orderTotal.'
                    </span>
                </p>    
                <input class="confirm-btn" type="submit" name="confirmPay" value="Confirm and pay">    
            </div>
                ';
        }
    ?>
    </form>
</div>
<script>
    //QTY+TOTAL ORDERS
    $(".item-qty").change(function(){
        let qty      = $(this).val();
        let original = $(this).parent().next(".total-item-price").next(".origial-price").html();
        let newTot   = qty * original;

        let orderTotal = $("#orderTotal").html();
        orderTotal -= $(this).parent().next(".total-item-price").text().slice(0, -1);
        orderTotal += newTot;

        $(this).parent().next(".total-item-price").text(newTot.toFixed(2));
        $("#orderTotal").html(orderTotal.toFixed(2));
});
</script>
<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>