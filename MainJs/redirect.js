function redirect(idx)
{
    switch(idx)
    {
        case "HOM":
            window.location.href = "https://localhost/Shopozo/MainPhp/index.php";
            break;
        case "ACC":
            window.location.href = "https://localhost/Shopozo/MainPhp/profile.php";
            break;
    }
}

function prodDetails(prodId)
{
    window.location.href = "https://localhost/Shopozo/MainPhp/prodDetails.php?prodId="+prodId;
}

function checkOutOneProd(prodId){
    qty = document.getElementById("prodQty").value;
    window.location.href = "https://localhost/Shopozo/MainPhp/checkOut.php?prodId="+prodId+"&qty="+qty;
}

function addToCart(prodId)
{
    qty = document.getElementById("prodQty").value;
    window.location.href = "https://localhost/Shopozo/MainPhp/addToCart.php?prodId="+prodId+"&qty="+qty;
}