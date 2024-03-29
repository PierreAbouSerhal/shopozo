<?php
session_set_cookie_params(0, "/", "localhost", false, true);
session_start();
include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");

$user      = array("userId" => "", "userOk" => false);
$userEmail = $userFname = $userLname = $userName  = $userPhone = $userCountry = $userStreet = $userCity = $userProvince = $userPostCode = "";
$isAdmin   = false;

//USER VERIFICATION FUNCTION
function evalLoggedUser($conx, $hashedToken, $user){
    $out = $user;

    $sql = "SELECT COUNT(*) AS rowNbr, userId FROM userTokens WHERE hashedToken = '".$hashedToken."';";
    $query = mysqli_query($conx, $sql);
    $res = mysqli_fetch_assoc($query);

    mysqli_free_result($query);

    if($res["rowNbr"] == 1)
    {
        $out["userId"] = $res["userId"];
        $out["userOk"] = true;
        return $out;
    }
}

function logout()
{
    if(isset($_COOKIE["userToken"]))
    {
        setcookie("userToken", "", 0, "/");
    }
    $_SESSION = array();
    session_destroy();
}

//REFRESH USERNAME
function refreshName($dbConx, $user)
{
    $name = $_SESSION["userName"];

    $sqlFetchName = "SELECT COUNT(*) AS rowNbr, name AS userName FROM users WHERE id = ".$user["userId"];

    $queryFetchName = mysqli_query($dbConx, $sqlFetchName);

    $resFetchName = mysqli_fetch_assoc($queryFetchName);

    mysqli_free_result($queryFetchName);

    if($resFetchName["rowNbr"] == 1)
    {
        $name = $resFetchName["userName"];
        return $name;
    }
}

function emailIsValid($email)
{
    $mailformat = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";

    if (preg_match($mailformat, $email)) 
    {
        return true;
    }
    else 
    {
        return false;
    }
}

function searchInTable($idx)
{
    switch($idx)
    {
        case "CTG":
            return "categories";
            break;
        case "PRD":
            return "products";
            break;
    }
}

if(isset($_SESSION["userToken"]) && isset($_SESSION["loggedin"]))
{
    if($_SESSION["loggedin"])
    {
        $hashedToken = hash("sha256", $_SESSION["userToken"]);

        $userEmail    = $_SESSION["userEmail"];
        $userFname    = $_SESSION["userFname"];
        $userLname    = $_SESSION["userLname"];
        $userName     = $_SESSION["userName"];
        $userPhone    = $_SESSION["userPhone"];
        $userCountry  = $_SESSION["userCountry"];
        $userStreet   = $_SESSION["userStreet"];
        $userCity     = $_SESSION["userCity"];
        $userProvince = $_SESSION["userProvince"];
        $userPostCode = $_SESSION["userPostCode"];
        $isAdmin      = $_SESSION["isAdmin"];
        $user         = evalLoggedUser($dbConx, $hashedToken, $user);
    }
}
else if(isset($_COOKIE["userToken"]))
{
    $hashedToken = hash("sha256", $_COOKIE["userToken"]);
    
    $user = evalLoggedUser($dbConx, $hashedToken, $user);

    if($user["userOk"])
    {
        $userId = $user["userId"];

        //REINITIATE SESSION VARIABLES
        $sqlCheck = "SELECT *, COUNT(*) AS rowNbr FROM users WHERE id = ".$userId.";";
        $queryCheck = mysqli_query($dbConx, $sqlCheck);
        $resCheck = mysqli_fetch_assoc($queryCheck);

        if($resCheck["rowNbr"] == 1)
        {
            $_SESSION["loggedin"]     = true;
            $_SESSION["userEmail"]    = $resCheck["email"];
            $_SESSION["userFname"]    = $resCheck["first"];
            $_SESSION["userLname"]    = $resCheck["last"];
            $_SESSION["userName"]     = $resCheck["first"].' '.$resCheck["last"];
            $_SESSION["userPhone"]    = $resCheck["phone"];
            $_SESSION["userCountry"]  = $resCheck["countryId"];
            $_SESSION["userStreet"]   = $resCheck["street"];
            $_SESSION["userCity"]     = $resCheck["city"];
            $_SESSION["userProvince"] = $resCheck["province"];
            $_SESSION["userPostCode"] = $resCheck["postalCode"];
            $_SESSION["userToken"]    = $_COOKIE["userToken"];
            $_SESSION["isAdmin"]      = ($resCheck["role"] == "ADMIN") ? true : false;

            $userEmail    = $_SESSION["userEmail"];
            $userFname    = $_SESSION["userFname"];
            $userLname    = $_SESSION["userLname"];
            $userName     = $_SESSION["userName"];
            $userPhone    = $_SESSION["userPhone"];
            $userCountry  = $_SESSION["userCountry"];
            $userStreet   = $_SESSION["userStreet"];
            $userCity     = $_SESSION["userCity"];
            $userProvince = $_SESSION["userProvince"];
            $userPostCode = $_SESSION["userPostCode"];
            $isAdmin      = $_SESSION["isAdmin"];

        }
    }
}
?>