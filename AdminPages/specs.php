<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
if(!$user["userOk"] || !$isAdmin)
{
    header("Location: ../MainPhp/index.php");
    exit();
}

$pageIdx = "SPC";
$specId = -1; //ADD SPEC
$title   = "";

if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT SPEC
{
    $specId = mysqli_real_escape_string($dbConx, $_GET["id"]);
}

//ON SUBMIT
if(isset($_POST["saveSpec"]) && !empty($_POST["specName"]))
{
    $sqlCheck = 'SELECT *, COUNT(*) AS rowNbr FROM specs WHERE id = '.$specId;

    $queryCheck = mysqli_query($dbConx, $sqlCheck);

    $resCheck = mysqli_fetch_assoc($queryCheck);

    $specName = mysqli_real_escape_string($dbConx, $_POST["specName"]);
    
    if($resCheck["rowNbr"] == 0)
    {
        $sqlUpdateSpec = 'INSERT INTO specs (name)
                            VALUES ("'.$specName.'")';
    }
    else
    {
        $sqlUpdateSpec = 'UPDATE specs SET name = "'.$specName.'" WHERE id = '.$specId;
    }

    $queryUpdateSpec = mysqli_query($dbConx, $sqlUpdateSpec);
    
}

$sqlSpecInfo = 'SELECT *, COUNT(*) AS rowNbr FROM specs WHERE id = '.$specId;

$querySpecInfo = mysqli_query($dbConx, $sqlSpecInfo);

$resSpecgInfo = mysqli_fetch_assoc($querySpecInfo);

if($resSpecgInfo["rowNbr"] == 0)
{
    $title = "Add Specs";
}
else
{
    $title =  "Edit ".$resSpecgInfo["name"];
}

include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

<h3 class="admin-page-header"><?php echo $title;?></h3>

<div class="main-admin-container">

    <div class="left-column">
        <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
    </div>
                    
    <form id="updatespecs" class="middle-column" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$specId; echo $action;?>" method="POST">

        <div id="dbname" class="input-container">
            <input type="text" id="isname" name="specName" autocomplete="off" placeholder=" " value="<?php echo $resSpecgInfo["name"]?>"/>
            <label id="lsname" for="isname" class="label-name">
                <span id="ssname" class="content-name">Spec Name</span>
            </label>
        </div>
        <div class="error-msg-container">
            <span id="errorSName"></span>
        </div>

        <div class="admin-save-btn-container">
            <input class="admin-save-btn" type="submit" value="Save" name="saveSpec">
        </div>

    </form>

</div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>