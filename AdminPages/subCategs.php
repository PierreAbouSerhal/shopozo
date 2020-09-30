<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
if(!$user["userOk"] || !$isAdmin)
{
    header("Location: ../MainPhp/index.php");
    exit();
}

$pageIdx = "SUB";
$subCategId = -1; //ADD SUB CATED
$title   = "";

if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT SUB CATEG
{
    $subCategId = mysqli_real_escape_string($dbConx, $_GET["id"]);
}

//ON SUBMIT
if(isset($_POST["saveSubCateg"]))
{
    if(!empty($_POST["subName"]) && !empty($_POST["subCategId"]) && !empty($_POST["subPic"]) && !empty($_POST["spec_list"]))
    {
        $sqlCheck   = 'SELECT *, COUNT(*) AS rowNbr FROM subcategories WHERE id = '.$subCategId;

        $queryCheck = mysqli_query($dbConx, $sqlCheck);

        $resCheck   = mysqli_fetch_assoc($queryCheck);

        $subName    = mysqli_real_escape_string($dbConx, $_POST["subName"]);
        $CategId    = mysqli_real_escape_string($dbConx, $_POST["CategId"]);
        $subPic     = mysqli_real_escape_string($dbConx, $_POST["subPic"]);
        $save       = true;
        $specIds    = array();

        if(!empty($_POST["spec_list"]))
        {
            foreach($_POST["spec_list"] as $specId)
            {
                $specId    = mysqli_real_escape_string($dbConx, $specId);
                $specIds[] = $specId;
            }
        }

        $subImg = $resCheck["picture"];

        //UPLOAD THE IMAGE
        if(file_exists($_FILES['sub-img']['tmp_name']) || is_uploaded_file($_FILES['sub-img']['tmp_name']))
        {
            $target_dir    = "../SubCategPics/";
            $target_file   = $target_dir . basename($_FILES["sub-img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $check         = getimagesize($_FILES["sub-img"]["tmp_name"]);//CHECK IF FALSE IMAGE
            $uploadOk      = 1;

            if($check === false || $_FILES["sub-img"]["size"] > 500000 ||
              ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "svg")) 
            {
                $uploadOk = 0;
                $save = false;
            }

            //DELETE IMAGE IF EXISTS
            if(file_exists($target_file))
            { 
                unlink($target_file);
            }

            if($uploadOk == 1)
            {
                move_uploaded_file($_FILES["sub-img"]["tmp_name"], $target_file);
                $subImg = '"/Shopozo/SubCategPics/'.basename($_FILES["sub-img"]["name"]).'"';
            }
        }

        if($resCheck["rowNbr"] == 0 && $save)
        {
            $sqlUpdateSub = 'INSERT INTO subCategories (name, categoryId, picture)
                                VALUES ("'.$subName.'", "'.$CategId.'", "'.$subImg.'")';
            
            $queryUpdateSub = mysqli_query($dbConx, $sqlUpdateSub);
            
            $subCategId = mysqli_insert_id($dbConx);
        }
        else if($resCheck["rowNbr"] == 1 && $save)
        {
            $sqlUpdateSub = 'UPDATE subCategories SET name = "'.$subName.'", 
                                categoryId = '.$CategId.', picture = "'.$subImg.'" WHERE id = '.$subCategId;

            $queryUpdateSub = mysqli_query($dbConx, $sqlUpdateSub);
        }

        //DELETE IF EXISTS
        $sqlDeleteSubSpecs = 'DELETE FROM subcategspecs WHERE subCategId = '.$subCategId;

        $queryDeleteSubSpecs = mysqli_query($dbConx, $sqlDeleteSubSpecs);

        if($queryDeleteSubSpecs)
        {
            foreach($specIds as $specId)
            {
                $sqlReInsert = 'INSERT INTO subcategspecs (subCategId, specId)
                                    VALUES ('.$subCategId.', '.$specId.')';
                
                $queryReInsert = mysqli_query($dbConx, $sqlReInsert);
                
            }
        }
    }
    
}

$sqlSubInfo = 'SELECT *, COUNT(*) AS rowNbr FROM subCategories WHERE id = '.$subCategId;

$querySubInfo = mysqli_query($dbConx, $sqlSubInfo);

$resSubInfo = mysqli_fetch_assoc($querySubInfo);

$sqlAllSpecs = 'SELECT * FROM specs ORDER BY NAME';

$queryAllSpecs = mysqli_query($dbConx, $sqlAllSpecs);

$sqlAllCateg = 'SELECT * FROM categories ORDER BY NAME';

$queryAllCateg = mysqli_query($dbConx, $sqlAllCateg);

$sqlFetchAllSubCategSpecs = 'SELECT * FROM subcategspecs WHERE subCategId = '.$subCategId;

$queryFetchAllSubCategSpecs = mysqli_query($dbConx, $sqlFetchAllSubCategSpecs);

$arraySubCategSpecs = array();

while($resFetchAllSubCategSpecs = mysqli_fetch_assoc($queryFetchAllSubCategSpecs))
{
    $arraySubCategSpecs[] = $resFetchAllSubCategSpecs["specId"];
}

if($resSubInfo["rowNbr"] == 0)
{
    $title = "Add Sub Categorie";
}
else
{
    $title =  "Edit ".$resSubInfo["name"];
}

include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

<h3 class="admin-page-header"><?php echo $title;?></h3>

<div class="main-admin-container">

    <div class="left-column">
        <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
    </div>
                    
    <form id="updatespecs" class="middle-column" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$subCategId; echo $action;?>" method="POST">

        <div id="dscname" class="input-container">
            <input type="text" id="iscname" name="subName" autocomplete="off" placeholder=" " value="<?php echo $resSubInfo["name"]?>"/>
            <label id="lscname" for="iscname" class="label-name">
                <span id="sscname" class="content-name">Sub Categorie Name</span>
            </label>
        </div>
        <div class="error-msg-container">
            <span id="errorSCName"></span>
        </div>

        <div class="select-container <?php 
                                        $selectBorder = (empty($resSubInfo["categoryId"])) ? "select-default" : "select-selected"; 
                                        echo $selectBorder;
                                    ?>">
            <div class="select-label">Categorie</div>
            <span class="select-wrapper">
                <select id="icbrand" type="text" name="categId">
                    <?php

                        if(empty($resSubInfo["categoryId"]))
                        {
                            echo '<option value="" hidden disabled selected value>Choose category</option>';
                        }

                        while($rowAllCateg = mysqli_fetch_assoc($queryAllCateg))
                        {
                            $selected = "";

                            if($resSubInfo["categoryId"] == $rowAllCateg["id"])
                            {
                                $selected = 'selected="selected"';
                            }
                            echo '<option value="'.$rowAllCateg["id"].'" '.$selected.'>'.$rowAllCateg["name"].'</option>';
                        }
                        mysqli_free_result($queryAllCateg);
                    ?>
                </select>
                <img src="../ShopozoPics/down-arrow.svg">
            </span>
        </div>
        <div class="error-msg-container">
            <span id="errorSCCateg"></span>
        </div>
               
        <div class="img-row">
            <div style="margin-top: 20px;">
                <span class="input-label">Image:</span>
                <div class="error-message-container">
                    <span id="errorImage" style = "margin: 0" class="error-message"></span>
                </div>
            </div>
            <div class="image-upload">
                <label for="file-input">
                <div class="upload-icon">
                    <img class="icon" src="<?php $src = (empty($resSubInfo["picture"])) ? "https://image.flaticon.com/icons/png/128/61/61112.png" : $resSubInfo["picture"]; echo $src?>">
                </div>
                </label>
            <input id="file-input" type="file" name="subPic"/>
            </div>
        </div>

        <div class="scroll-container">                            
            <table class="mng-table">
                <thead>
                    <tr class="border-bottom table-header">
                        <th>Is Select</th>
                        <th style="text-align:left" class="col-name">Spec Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $lineId = 1;

                    while($resAllSpecs = mysqli_fetch_assoc($queryAllSpecs))
                    {
                        $checked  = (in_array($resAllSpecs["id"], $arraySubCategSpecs)) ? "checked" : "";
                        $backColor = "";

                        if($lineId % 2 == 0)
                        {
                            $backColor = 'style="background-color: rgb(235, 235, 235);"';
                        }

                        echo '<tr '.$backColor.' class="border-bottom">
                                <td style="text-align: center">
                                    <input type="checkbox" class="spec_list" name="spec_list[]"  value="'.$resAllSpecs["id"].'" '.$checked.'>
                                </td>
                                <td>'.$resAllSpecs["name"].'</td>
                            </tr>';
                        $lineId ++;
                    }
                    mysqli_free_result($queryAllSpecs);
                ?>
                </tbody>
            </table>
        </div>
        <div class="error-msg-container">
            <span id="errorSCCateg"></span>
        </div>

        <div class="admin-save-btn-container">
            <input class="admin-save-btn" type="submit" value="Save" name="saveSubCateg">
        </div>

    </form>

</div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>