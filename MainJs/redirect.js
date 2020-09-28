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
    }
}

function prodDetails(prodId)
{
    window.location.href = "http://localhost/Shopozo/MainPhp/prodDetails.php?prodId="+prodId;
}

function checkOutOneProd(prodId){
    qty = document.getElementById("prodQty").value;
    window.location.href = "http://localhost/Shopozo/MainPhp/checkOut.php?prodId="+prodId+"&qty="+qty;
}