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
    
    if($subCategId != -1)
    {
        $sqlFetchSubCateg .= ' AND id = $subCategId';
    }

    $queryFetchSubCateg = mysqli_query($dbConx, $sqlFetchSubCateg);

    if(mysqli_num_rows($queryFetchSubCateg) == 0)
    {
        header("Location: index.php");
        exit();
    }
    
?>
<h2 class="categories-page-header"><?php echo $title?></h2>

<div class="categ-and-products-container">
    <div class="sub-categories-container">
        <ul>
            <?php
                
                while($resFetchSubCateg = mysqli_fetch_assoc($queryFetchSubCateg))
                {
                    echo '<li>
                            <a href="../MainPhp/categories.php?categId='.$resFetchSubCateg["categoryId"].'"&subCategId='.$resFetchSubCateg["id"].'>'.$resFetchSubCateg["name"].'</a>
                          </li>';
                }
            ?>
        </ul>
    </div>
    <div class="products-container">

    </div>
</div>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>