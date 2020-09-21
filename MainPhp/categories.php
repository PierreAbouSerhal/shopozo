<?php
    $categId = "";

    if(!isset($_GET["categId"]) || !is_numeric($_GET["categId"]) || !isset($_GET["subCategId"]) || !is_numeric($_GET["subCategId"]))
    {
        header("Location: index.php");
        exit();
    }

    $title = "CTG_".$_GET["categId"];

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainHeader.php");

    $categId    = mysqli_real_escape_string($dbConx, $_GET["categId"]);
    $subCategId = mysqli_real_escape_string($dbConx, $_GET["subCategId"]);

    $sqlFetchSubCateg = 'SELECT *
                         FROM subCategories
                         WHERE categoryId = '.$categId;

    $queryFetchSubCateg = mysqli_query($dbConx, $sqlFetchSubCateg);

    if(mysqli_num_rows($queryFetchSubCateg) == 0)
    {
        header("Location: index.php");
        exit();
    }

    $sqlFetchProducts = 'SELECT prod.*,
                                prodSpecs.value AS prodSpec,
                                prodPics.picture AS prodPic,
                                specs.name AS prodSpec
                         FROM products AS prod
                         JOIN (
                             SELECT prodSpecs AS prodSpecs
                             JOIN  
                             ON prod.id = prodSpecs.productId
                            ) 
                         ';
    
?>
<h1 class="categ-page-header"><?php echo $title?></h1>

<div class="categ-and-products-container">
    <div class="sub-categ-container">
        <span class="sub-categ-title">Shop By Categorie</span>       
        <ul>
            <?php
                
                while($resFetchSubCateg = mysqli_fetch_assoc($queryFetchSubCateg))
                {
                    echo '<li>
                            <a href="../MainPhp/categories.php?categId='.$resFetchSubCateg["categoryId"].'&subCategId='.$resFetchSubCateg["id"].'">'.$resFetchSubCateg["name"].'</a>
                          </li>';
                }
            ?>
        </ul>
    </div>
    <div class="products-container">
            <span class="sub-categ-title">Product List</span>

    </div>
</div>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>