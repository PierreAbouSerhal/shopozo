<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
   
    if(!$user["userOk"] || !isset($_GET["orderId"]))
    {
        header("Location: index.php");
        exit();
    }

    $orderId = mysqli_real_escape_string($dbConx, $_GET["orderId"]);

    if($orderId != -1)
    {
        $sqlVerif = 'SELECT COUNT(*) AS rowNbr FROM orders WHERE id = '.$orderId.' AND isCanceled = 0';

        $queryVerif = mysqli_query($dbConx, $sqlVerif);

        $resVerif = mysqli_fetch_assoc($queryVerif);

        if($resVerif["rowNbr"] != 1)
        {
            header("Location: index.php");
            exit();
        }
    }

    $fop1 = ($orderId > 0) ? "WHERE orders.id = ".$orderId." AND orders.isCanceled = 0" : "WHERE orders.isCanceled = 0";
    
    $sqlFetchOrders = 'SELECT * FROM orders '.$fop1.' ORDER BY id';

    $queryFetchOrders = mysqli_query($dbConx, $sqlFetchOrders);

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
            $selectInput = (strtotime(date("y-m-d")) < (strtotime($resFetchOrders["creationDate"]. ' + 1 day'))) ? '<input class="select-order" type="checkbox" name="orders[]" value="'.$orderNum.'">' : '';
            
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
                        '.$selectInput.'
                        <table class="order-table box-shadow" cellpadding=10 style="border-collapse:collapse; table-layout:fixed;width=100%">
                            <tr class="order-table-header">
                                <th>ITEM</th>
                                <th>PRICE</th>
                                <th>QTY</th>
                                <th>TOTAL</th>
                            </tr>';

            $sqlFetchOrderDet = 'SELECT ordersDetails.*,
                                        products.* 
                                FROM ordersDetails
                                JOIN products
                                ON products.id = ordersDetails.productId
                                WHERE ordersDetails.orderId = '.$orderNum.'
                                ORDER BY ordersDetails.orderId';
        
            $queryFetchOrdersDet = mysqli_query($dbConx, $sqlFetchOrderDet);

            $totalOrder = 0;
            $rowCnt = 1;

            while($resFetchOrdersDet = mysqli_fetch_assoc($queryFetchOrdersDet))
            {
                $itemName   = $resFetchOrdersDet["name"];
                $itemPrice  = $resFetchOrdersDet["productPrice"];
                $itemQty    = $resFetchOrdersDet["qty"];
                $lineTotal  = $resFetchOrdersDet["lineTotal"];
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
        <ul class="ul-tips">
            <li>
                <span class="cancelation-tip">Please note that orders can only be canceled in the first 24h of their creation.</span>
            </li>
            <li>
                <span class="cancelation-tip">Tick the check boxes to select your orders.</span>
            </li>
        </ul>
        <input class="cancelation-btn" type="submit" name="cancel" value="Cancel Orders">
    </div>
</form>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainFooter.html");
?>