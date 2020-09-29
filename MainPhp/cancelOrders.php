<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/mailSetup.php");

    if(!$user["userOk"] || !isset($_POST["orders"]))
    {
        header("Location: index.php");
        exit();
    }

    $html = '<html><body>
            <p><strong>You have canceled The following orders:</strong></p>
            <p>';

    $orders = $_POST["orders"];

    foreach ($orders as $orderId)
    {   
        $sqlFetchRelatedProds = 'SELECT * FROM ordersdetails WHERE orderId = '.$orderId;

        $queryFetchRelatedProds = mysqli_query($dbConx, $sqlFetchRelatedProds);

        while($resFetchRelatedProds = mysqli_fetch_assoc($queryFetchRelatedProds))
        {
            $prodId  = $resFetchRelatedProds["productId"];
            $prodQty = $resFetchRelatedProds["qty"];

            $sqlUpdateStock = 'UPDATE products SET stock = stock + '.$prodQty.' WHERE id = '.$prodId;

            $sqlCancelOrder = 'UPDATE orders SET isCanceled = 1 WHERE orders.id = '.mysqli_real_escape_string($dbConx, $orderId);

            $sqlDeleteCart  = 'DELETE FROM shippingCarts WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

            $queryUpdateStock = mysqli_query($dbConx, $sqlUpdateStock);
        }

        mysqli_free_result($queryFetchRelatedProds);

        $queryCancelOrder = mysqli_query($dbConx, $sqlCancelOrder);
        $html .= $orderId.', ';
    }

    $html = substr($html, 0, -2);
    
    $subject = "Your Order Cancelation";

    $html .='<p>Shopozo Support</p>
             </body></html>';

    $from = array("name" => "Shopozo", "email" => $smtp["username"]);

    $to = array(
        array(
            "name" => $userFname.' '.$userLname,
            "email" => $userEmail
        )
    );

    //SEND THE MAIL
    $jmomailer = new JMOMailer(true, $smtp);
            
    $jmomailer->mail($to, $subject, $html, $from);

    header("Location: orders.php?orderId=-1");
    exit();

?>