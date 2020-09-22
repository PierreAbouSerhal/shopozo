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

    $sqlFetchProducts = 'SELECT products.*, productpics.picture
                         FROM (
                                 SELECT products.*, subcategories.categoryId
                                 FROM products JOIN subcategories ON products.subCategId = subcategories.id
                                 WHERE subcategories.categoryId = 1
                             )AS products
                         JOIN productpics ON productpics.productId = products.id
                         WHERE productpics.isPrimary = 1
                         ORDER BY products.totalWatchers DESC';
    
    $queryFetchProducts = mysqli_query($dbConx, $sqlFetchProducts);
    
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

            <div class="product-details-contaier">
                <div class="product-img-container">
                    <img class="product-img" src="../ProductPics/iphone11front.png" alt="iphone 11">
                </div>
                <div class="product-info">
                    <h3 class="product-name">Iphone 11 Pro Max 256GB NEW Ndif 3a sekin ya batikh</h3>
                    <div class="product-Condition">New</div>
                    <div class="product-price">1299$</div>
                    <div class="product-watchers">127 Watchers</div>
                </div>
            </div>
    </div>
</div>
<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>