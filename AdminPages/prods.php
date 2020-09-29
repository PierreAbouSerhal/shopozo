<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        header("Location: ../MainPhp/index.php");
        exit();
    }

    $pageIdx = "PRD";
    $prodId = -1; //ADD PRODUCT
    $title = "";

    if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT PRODUCT
    {
        $prodId = mysqli_real_escape_string($dbConx, $_GET["id"]);
    }


    //REFRESH PAGE
    $sqlProdInfo      = "SELECT products.*, COUNT(*) AS rowNbr FROM products WHERE products.id = ".$prodId.";";

    $queryProdInfo    = mysqli_query($dbConx, $sqlProdInfo);

    $sqlAllBrands     = "SELECT brands.id AS brandId, brands.name FROM brands ORDER BY NAME";

    $queryAllBrands   = mysqli_query($dbConx, $sqlAllBrands);

    $sqlAllSubCateg   = 'SELECT subcategories.id AS subCategId, subcategories.name FROM subcategories ORDER BY NAME';

    $queryAllSubCateg = mysqli_query($dbConx, $sqlAllSubCateg);

    $sqlAllPics       = 'SELECT productPics.picture FROM productPics WHERE productPics.productId = '.$prodId;

    $resProdInfo = mysqli_fetch_assoc($queryProdInfo);

    $sqlProdSpecs = 'SELECT specs.name, 
                            specs.id AS specId,
                            productspecs.value
                     FROM specs
                     JOIN productspecs
                     ON specs.id = productspecs.specId
                     WHERE productspecs.productId = '.$prodId;
    
    $queryProdSpecs = mysqli_query($dbConx, $sqlProdSpecs);

    $sqlProdPics  = 'SELECT productPics.picture
                     FROM productPics
                     WHERE productPics.prodId = '.$prodId;

    $queryProdPics = mysqli_query($dbConx, $sqlProdPics);

    if($resProdInfo["rowNbr"] == 0)
    {
        $title = "Add product";
    }
    else
    {
        $title =  "Edit ".$resProdInfo["name"];
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

?>

      <h3 class="admin-page-header"><?php echo $title;?></h3>

      <div class="main-admin-container">

            <div class="left-column">
                <?php include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/manageLinks.html");?>
            </div>
                            
            <form class="middle-column" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$prodId; echo $action;?>" method="POST" enctype="multipart/form-data" onsubmit="return validateProds();">

                <div id="dpname" class="input-container">
                    <input type="text" id="ipname" name="prodName" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["name"]?>"/>
                    <label id="lpname" for="ipname" class="label-name">
                        <span id="spname" class="content-name">Product Name</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPName'"></span>
                </div>

                <div id="dpprice" class="input-container">
                    <input type="text" id="ippprice" name="prodPrice" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["price"]?>"/>
                    <label id="lppprice" for="ippprice" class="label-name">
                        <span id="sppprice" class="content-name">Product Price</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPPrice'"></span>
                </div>

                <div id="dpdiscount" class="input-container">
                    <input type="text" id="ipddiscount" name="prodDiscount" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["discount"]?>"/>
                    <label id="lpddiscount" for="ipddiscount" class="label-name">
                        <span id="spddiscount" class="content-name">Product Discount</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPDiscount'"></span>
                </div>

                <div id="dpstock" class="input-container">
                    <input type="text" id="ipstock" name="prodStock" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["stock"]?>"/>
                    <label id="lpstock" for="ipstock" class="label-name">
                        <span id="spstock" class="content-name">Product Stock</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPStock'"></span>
                </div>

                <div id="dpdescr" class="input-container">
                    <input type="text" id="ipdescr" name="prodDescr" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["descr"]?>"/>
                    <label id="lpdescr" for="ipdescr" class="label-name">
                        <span id="spdescr" class="content-name">Product Description</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPDescr'"></span>
                </div>

                <div id="dpcond" class="input-container">
                    <input type="text" id="ipcond" name="prodCond" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["prodCond"]?>"/>
                    <label id="lpcond" for="ipcond" class="label-name">
                        <span id="spcond" class="content-name">Product Condition</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPCond'"></span>
                </div>

                <div id="dpship" class="input-container">
                    <input type="text" id="ipship" name="prodShip" autocomplete="off" placeholder=" " value="<?php echo $resProdInfo["shipTime"]?>"/>
                    <label id="lpship" for="ipship" class="label-name">
                        <span id="spship" class="content-name">Shipping Time</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorPShip'"></span>
                </div>

                <div class="select-container <?php 
                                                $selectBorder = (empty($resProdInfo["subCategId"])) ? "select-default" : "select-selected"; 
                                                echo $selectBorder;
                                             ?>">
                    <div class="select-label">Sub Categorie</div>
                    <span class="select-wrapper">
                        <select id="icsubcated" type="text" name="prodSubCateg">
                            <?php

                                if(empty($resProdInfo["subCategId"]))
                                {
                                    echo '<option value="" hidden disabled selected value>Choose sub categ</option>';
                                }

                                while($rowAllCateg = mysqli_fetch_assoc($queryAllSubCateg))
                                {
                                    $selected = "";

                                    if($resProdInfo["subCategId"] == $rowAllCateg["subCategId"])
                                    {
                                        $selected = 'selected="selected"';
                                    }
                                    echo '<option value="'.$rowAllCateg["subCategId"].'" '.$selected.'>'.$rowAllCateg["name"].'</option>';
                                }
                                mysqli_free_result($queryAllSubCateg);
                            ?>
                        </select>
                        <img src="../ShopozoPics/down-arrow.svg">
                    </span>
                </div>
                <div class="error-msg-container">
                    <span id="errorPSubCateg'"></span>
                </div>


                <div class="select-container <?php 
                                                $selectBorder = (empty($resProdInfo["brandId"])) ? "select-default" : "select-selected"; 
                                                echo $selectBorder;
                                             ?>">
                    <div class="select-label">Brand</div>
                    <span class="select-wrapper">
                        <select id="icbrand" type="text" name="prodBrand" onchange="document.getElementById('icou').sty">
                            <?php

                                if(empty($resProdInfo["brandId"]))
                                {
                                    echo '<option value="" hidden disabled selected value>Choose brand</option>';
                                }

                                while($rowAllBrands = mysqli_fetch_assoc($queryAllBrands))
                                {
                                    $selected = "";

                                    if($resProdInfo["brandId"] == $rowAllBrands["brandId"])
                                    {
                                        $selected = 'selected="selected"';
                                    }
                                    echo '<option value="'.$rowAllBrands["brandId"].'" '.$selected.'>'.$rowAllBrands["name"].'</option>';
                                }
                                mysqli_free_result($queryAllBrands);
                            ?>
                        </select>
                        <img src="../ShopozoPics/down-arrow.svg">
                    </span>
                </div>
                <div class="error-msg-container">
                    <span id="errorPBrand'"></span>
                </div>
                
                <h3 style="text-decoration: underline;margin-bottom:10px;">Product Specs</h3>
                <?php
                    while($resProdSpecs = mysqli_fetch_assoc($queryProdSpecs))
                    {
                        echo '<div id="dpspec'.$resProdSpecs["specId"].'" class="input-container">
                                <input type="text" id="ipspec'.$resProdSpecs["specId"].'" name="prodShip" autocomplete="off" placeholder=" " value="'.$resProdSpecs["value"].'"/>
                                <label id="lpspec'.$resProdSpecs["specId"].'" for="ipspec'.$resProdSpecs["specId"].'" class="label-name">
                                    <span id="spspec'.$resProdSpecs["specId"].'" class="content-name">'.$resProdSpecs["name"].'</span>
                                </label>
                            </div>
                            
                            <div class="error-msg-container">
                                <span id="errorspec'.$resProdSpecs["specId"].'"></span>
                            </div>';
                    }
                ?>                

                <h3 style="text-decoration: underline;margin-bottom:10px;">Product Pics</h3>
                

                <div class="image-upload">
                    <label for="file-input-">
                        <div class="upload-icon">
                            <img class="" src="">
                        </div>
                    </label>
                    <input id="file-input" type="file" name=""/>
                </div>


            </form>

            <div class="right-column">

            </div>
      </div>

<!-- CLOSING PAGE CONTAINER, BODY, HTML -->
</div>
</body>
</html>