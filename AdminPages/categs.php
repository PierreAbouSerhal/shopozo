<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
if(!$user["userOk"] || !$isAdmin)
{
    header("Location: ../MainPhp/index.php");
    exit();
}

$pageIdx = "CTG";
$categId = -1; //ADD CATEGORIE
$title   = "";

if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT CATEGORIE
{
    $categId = mysqli_real_escape_string($dbConx, $_GET["id"]);
}

//ON SUBMIT
if(isset($_POST["saveCateg"]) && !empty($_POST["categName"]))
{
    $sqlCheck = 'SELECT *, COUNT(*) AS rowNbr FROM categories WHERE id = '.$categId;

    $queryCheck = mysqli_query($dbConx, $sqlCheck);

    $resCheck = mysqli_fetch_assoc($queryCheck);

    $categName = mysqli_real_escape_string($dbConx, $_POST["categName"]);
    
    if($resCheck["rowNbr"] == 0)
    {
        $sqlUpdateCateg = 'INSERT INTO categories (name)
                            VALUES ("'.$categName.'")';
    }
    else
    {
        $sqlUpdateCateg = 'UPDATE categories SET name = "'.$categName.'" WHERE id = '.$categId;
    }

    $queryUpdateCateg = mysqli_query($dbConx, $sqlUpdateCateg);
    
}

$sqlCategInfo = 'SELECT *, COUNT(*) AS rowNbr FROM categories WHERE id = '.$categId;

$queryCategInfo = mysqli_query($dbConx, $sqlCategInfo);

$resCategInfo = mysqli_fetch_assoc($queryCategInfo);

if($resCategInfo["rowNbr"] == 0)
{
    $title = "Add Categorie";
}
else
{
    $title =  "Edit ".$resCategInfo["name"];
}

include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

<h3 class="admin-page-header"><?php echo $title;?></h3>

<div class="main-admin-container">

    <div class="left-column">
        <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
    </div>
                    
    <form id="updateCategories" class="middle-column" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$categId; echo $action;?>" method="POST">

        <div id="dbname" class="input-container">
            <input type="text" id="icname" name="categName" autocomplete="off" placeholder=" " value="<?php echo $resCategInfo["name"]?>"/>
            <label id="lcname" for="icname" class="label-name">
                <span id="scname" class="content-name">Categorie Name</span>
            </label>
        </div>
        <div class="error-msg-container">
            <span id="errorCName"></span>
        </div>

        <div class="admin-save-btn-container">
            <input class="admin-save-btn" type="submit" value="Save" name="saveCateg">
        </div>

    </form>

</div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>