<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/mailSetup.php");

    if(!$user["userOk"] || !isset($_GET["orderId"]))
    {
        header("Location: index.php");
        exit();
    }

    $orderId = mysqli_real_escape_string($dbConx, $_GET["orderId"]);

    if($orderId != -1)
    {
        $sqlVerif = 'SELECT COUNT(*) AS rowNbr FROM orders WHERE id = '.$orderId;

        $queryVerif = mysqli_query($dbConx, $sqlVerif);

        $resVerif = mysqli_fetch_assoc($queryVerif);

        if($resVerif["rowNbr"] != 1)
        {
            header("Location: index.php");
            exit();
        }
    }

    $fop1 = ($orderId > 0) ? "WHERE orders.id = ".$orderId : "";
    $fop2 = ($orderId > 0) ? "WHERE ordersDetails.orderId = ".$orderId : "";
    
    $sqlFetchOrders = 'SELECT * FROM orders '.$fop1.' ORDER BY id';

    $queryFetchOrders = mysqli_query($dbConx, $sqlFetchOrders);

    $sqlFetchOrderDet = 'SELECT ordersDetails.*,
                                products.* 
                        FROM ordersDetails
                        JOIN products
                        ON products.id = ordersDetails.productId
                        '.$fop2.'
                        ORDER BY ordersDetails.orderId';
            
    $queryFetchOrdersDet = mysqli_query($dbConx, $sqlFetchOrderDet);

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");
?>

<title>My orders | Shopozo</title>
<link rel="stylesheet" href="../MainCss/mainHeader.css">
<link rel="stylesheet" href="../MainCss/orders.css">
<link rel="stylesheet" href="../MainCss/checkout.css">
<link rel="stylesheet" href="../MainCss/mainFooter.css">

<script src="../MainJs/redirect.js"></script>

</head>
<body>
<div class="container">
    <div class="title-container">
        <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="shopozo" onclick="redirect('HOM');">
        <h2 class="checkout-header">My orders</h2>
    </div>

<form class="main-container" method="POST" action="cancelOrders.php">
    <div class="left-column">
    <?php

        while($resFetchOrders = mysqli_fetch_assoc($queryFetchOrders))
        {
            $orderNum = $resFetchOrders["id"];
            $orderDate = $resFetchOrders["creationDate"];
            
            echo '
                <div class="order-container">
                    <div class="order-num-date-container">
                        <div>Order Number: <strong>'.$orderNum.'</strong></div>
                        <div>Order Date: <strong>'.$orderDate.'</strong></div>
                    </div>
                    <div class="order-summary">
                        <h2 class="order-summary-title">
                            Order Summary
                        </h2>
                        <input class="select-order" type="checkbox">
                        <table class="order-table box-shadow" cellpadding=10 style="border-collapse:collapse; table-layout:fixed;width=100%">
                            <tr class="order-table-header">
                                <th>ITEM</th>
                                <th>PRICE</th>
                                <th>QTY</th>
                                <th>TOTAL</th>
                            </tr>';

            $totalOrder = 0;
            $rowCnt = 1;
            while($resFetchOrdersDet = mysqli_fetch_assoc($queryFetchOrdersDet))
            {
                $itemName = $resFetchOrdersDet["name"];
                $itemPrice = $resFetchOrdersDet["productPrice"];
                $itemQty   = $resFetchOrdersDet["qty"];
                $lineTotal = $resFetchOrdersDet["lineTotal"];
                $totalOrder += $lineTotal;
                $background = ($rowCnt % 2 == 0) ? "light-grey" : "";
                echo '<tr class="item-details-row '.$background.'">
                        <td>'.$itemName.'</td>
                        <td>'.$itemPrice.'$</td>
                        <td class="item-quantity">'.$itemQty.'</td>
                        <td>'.$lineTotal.'$</td>
                      </tr>';
                $rowCnt ++;
            }        
                    
            echo            '<tr>
                                <td></td>
                                <td colspan="2"><strong>Order Total:</strong></td>
                                <td><strong>'.$totalOrder.'$</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                  ';
        }

    ?>
    </div>
    <div class="right-column">
        <h2>Order cancelation<h2>
        <span>Please Note that</span>
    </div>
</form>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>