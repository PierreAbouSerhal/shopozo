function redirect(idx)
{
    switch(idx)
    {
        case "HOM":
            window.location.href = "http://localhost/Shopozo/MainPhp/index.php";
            break;
        case "ACC":
            window.location.href = "http://localhost/Shopozo/MainPhp/profile.php";
            break;
        case "SHP":
            window.location.href = "http://localhost/Shopozo/MainPhp/shoppingCart.php";
            break;
        case "CHK":
            window.location.href = "http://localhost/Shopozo/MainPhp/checkOut.php";
            break;
        case "SIN":
            window.location.href = "http://localhost/Shopozo/MainPhp/signin.php";
            break;
        case "REG":
            window.location.href = "http://localhost/Shopozo/MainPhp/register.php";
            break;
        case "SAV":
            window.location.href = "http://localhost/Shopozo/MainPhp/savedProds.php";
            break;
        case "WAT":
            window.location.href = "http://localhost/Shopozo/MainPhp/watchList.php";
            break;
        case "ADM":
            window.location.href = "http://localhost/Shopozo/AdminPages/generalInfo.php";
            break;
    }
}

function prodDetails(prodId)
{
    window.location.href = "http://localhost/Shopozo/MainPhp/prodDetails.php?prodId="+prodId;
}

function checkOutOneProd(prodId)
{
    qty = document.getElementById("prodQty").value;
    window.location.href = "http://localhost/Shopozo/MainPhp/checkOut.php?prodId="+prodId+"&qty="+qty;
}

function addToCart(prodId)
{
    qty = document.getElementById("prodQty").value;
    window.location.href = "http://localhost/Shopozo/MainPhp/addToCart.php?prodId="+prodId+"&qty="+qty;
}