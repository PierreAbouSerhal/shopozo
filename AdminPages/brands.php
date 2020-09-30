<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
if(!$user["userOk"] || !$isAdmin)
{
    header("Location: ../MainPhp/index.php");
    exit();
}

$pageIdx = "BRD";
$brandId = -1; //ADD BRAND
$title   = "";

if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT BRAND
{
    $brandId = mysqli_real_escape_string($dbConx, $_GET["id"]);
}

//ON SUBMIT
if(isset($_POST["saveBrand"]) && !empty($_POST["brandName"]))
{
    $sqlCheck = 'SELECT *, COUNT(*) AS rowNbr FROM brands WHERE id = '.$brandId;

    $queryCheck = mysqli_query($dbConx, $sqlCheck);

    $resCheck = mysqli_fetch_assoc($queryCheck);

    $brandName = mysqli_real_escape_string($dbConx, $_POST["brandName"]);
    
    if($resCheck["rowNbr"] == 0)
    {
        $sqlUpdateBrd = 'INSERT INTO brands (name)
                            VALUES ("'.$brandName.'")';
    }
    else
    {
        $sqlUpdateBrd = 'UPDATE brands SET name = "'.$brandName.'" WHERE id = '.$brandId;
    }

    $queryUpdateBrand = mysqli_query($dbConx, $sqlUpdateBrd);
    
}

$sqlBrandInfo = 'SELECT *, COUNT(*) AS rowNbr FROM brands WHERE id = '.$brandId;

$queryBrandInfo = mysqli_query($dbConx, $sqlBrandInfo);

$resBrandIndo = mysqli_fetch_assoc($queryBrandInfo);

if($resBrandIndo["rowNbr"] == 0)
{
    $title = "Add brand";
}
else
{
    $title =  "Edit ".$resBrandIndo["name"];
}

include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

<h3 class="admin-page-header"><?php echo $title;?></h3>

<div class="main-admin-container">

    <div class="left-column">
        <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
    </div>
                    
    <form id="updateBrands" class="middle-column" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$brandId; echo $action;?>" method="POST">

        <div id="dbname" class="input-container">
            <input type="text" id="ibname" name="brandName" autocomplete="off" placeholder=" " value="<?php echo $resBrandIndo["name"]?>"/>
            <label id="lbname" for="ibname" class="label-name">
                <span id="sbname" class="content-name">brand Name</span>
            </label>
        </div>
        <div class="error-msg-container">
            <span id="errorBName"></span>
        </div>

        <div class="admin-save-btn-container">
            <input class="admin-save-btn" type="submit" value="Save" name="saveBrand">
        </div>

    </form>

</div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>