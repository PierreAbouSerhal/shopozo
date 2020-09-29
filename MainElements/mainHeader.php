<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {
        logout();
    }

    $sqlAllCateg = 'SELECT * FROM subCategories ORDER BY name';

    $queryAllCateg = mysqli_query($dbConx, $sqlAllCateg);

    $sqlPopularCateg = 'SELECT c.*,
                               SUM(sc.popularityPts) AS totPts 
                        FROM categories AS c 
                        JOIN subCategories AS sc
                        ON c.id = sc.categoryId
                        GROUP BY c.id
                        ORDER BY totPts DESC
                        LIMIT 6';

    $queryPopularCateg = mysqli_query($dbConx, $sqlPopularCateg);
    
    if(strpos($title, "_") !== false)
    {
        $detTitle = explode("_", mysqli_real_escape_string($dbConx, $title));
        $idx = $detTitle[0];
        $id  = $detTitle[1];

        $sqlFetchTitle = 'SELECT name FROM '.searchInTable($idx).' WHERE id = '.$id;

        $queryFetchTitle = mysqli_query($dbConx, $sqlFetchTitle);

        $resFetchTitle = mysqli_fetch_assoc($queryFetchTitle);

        $title = $resFetchTitle["name"];
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");
?>

    <link rel="stylesheet" href="../MainCss/mainHeader.css">
    <link rel="stylesheet" href="../MainCss/sideNav.css">
    <link rel="stylesheet" href="../MainCss/profile.css">
    <link rel="stylesheet" href="../MainCss/inputFields.css">
    <link rel="stylesheet" href="../MainCss/index.css">
    <link rel="stylesheet" href="../MainCss/carousel.css">
    <link rel="stylesheet" href="../MainCss/categories.css">
    <link rel="stylesheet" href="../MainCss/mainFooter.css">
    <link rel="stylesheet" href="../MainCss/searchResult.css">
    <link rel="stylesheet" href="../MainCss/prodDetails.css">
    <link rel="stylesheet" href="../MainCss/shoppingCart.css">
    <link rel="stylesheet" href="../MainCss/savedWatch.css">

    <!-- CAROUSEL -->
    <link rel="stylesheet" type="text/css" href="../slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../slick/slick-theme.css"/>

    <script src="../MainJs/sideNav.js"></script>
    <script src="../MainJs/formValidation.js"></script>
    <script src="../MainJs/redirect.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <title><?php echo $title?></title>
</head>
<body>
    <!-- INDEPENDENT FROM MAIN CONTAINER -->
    <div class="header-bottom-line others">
        <div class="header-container container">
            <div class="header-left-corner">
                <span class="links">
                    <?php
                        if($user["userOk"])
                        {
                            echo '
                                <span>Hi '.$userName.'!</span>
                            ';
                        }
                        else
                        {
                            echo '
                            <span>Hi!</span>
                                <a href="../MainPhp/signin.php">Signin</a>
                                <span>or</span>
                                <a href="../MainPhp/register.php">register</a>
                            </span>
                            ';
                        }
                    ?>
                </span>
            </div>
            <div class="header-right-corner">
                <?php
                    if($user["userOk"])
                    {
                        echo '
                        <a href="../MainPhp/Profile.php">My Account</a>
                        <a href="../MainPhp/watchList.php">Watch List</a>
                        <img class="cart-img" src="../ShopozoPics/shopping-cart.svg" style="margin-left: 10%;" onclick="redirect(\'SHP\')">      
                        ';
                    }
                ?>
            </div>
        </div>
    </div>

<div class="container">

    <!-- MOBILE HEADER -->

    <div id="mySidenav" class="sidenav mobile">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <span style="white-space: nowrap">Main Cartegories</span>
                    
        <?php
            while($resPopularCateg = mysqli_fetch_assoc($queryPopularCateg))
            {
                echo '<a href="../MainPhp/categories.php?categId='.$resPopularCateg["id"].'&subCategId=-1">'.$resPopularCateg["name"].'</a>';
            }
        ?>
    </div>

     <div class="mobile-header-container mobile">
        <div class="mobile-logo-container">
            <img class="mobile-logo-img" src="../ShopozoPics/shopozo-logo.png" alt="shopozo" onclick="redirect('HOM');">
        </div>
        <div class="mobile-menu-header">
            <?php
                if($user["userOk"])
                {
                    echo '
                        <img class="menu-img" src="../ShopozoPics/user.svg" alt="profile" onclick="redirect(\'ACC\');">
                        <img class="menu-img" src="../ShopozoPics/watch-list.svg" alt="watch-list" onclick="redirect(\'WAT\');">
                        <img class="menu-img" src="../ShopozoPics/shopping-cart.svg" alt="cart" onclick="redirect(\'SHP\')">
                    ';
                }
            ?>
            <img class="menu-img" src="../ShopozoPics/emptyheart.svg" alt="saved" onclick="redirect('SAV');">
            <img class="menu-img" src="../ShopozoPics/hamberger.svg" alt="menu" onclick="openNav()">
        </div>
     </div>
     
     <form method="GET" action="searchResult.php" class="mobile-search-bar-container mobile">
        <div class="mobile-search-wrapper">
            <input class="mobile-search-input" type="text" name="userSearch" placeholder="Search for anything" <?php
                                                                                                                    if(isset($_GET["userSearch"]))
                                                                                                                    {
                                                                                                                        echo 'value="'.$_GET["userSearch"].'"';
                                                                                                                    }
                                                                                                                ?>>
        </div>
        <input type="submit" class="mobile-search-btn" value="">
     </form>

    <!-- Normal header -->

    <form method="GET" action="searchResult.php" class="search-bar-container others">
        <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="Shopozo" onclick="redirect('HOM')">
        <span class="search-bar">
            <span class="search-bar-input">
                <span class="search-icon"></span>
                <input type="text" name="userSearch" placeholder="Search for anything" <?php
                                                                                            if(isset($_GET["userSearch"]))
                                                                                            {
                                                                                                echo 'value="'.$_GET["userSearch"].'"';
                                                                                            }
                                                                                        ?>>
            </span>
            <span class="categories-combo-box-container">
                <select class="combo-box" name="subCategId" id="categ">
                    <?php
                        echo '<option value="-1"> All Categories';

                        $subId = "";
                        
                        if(isset($_GET["subCategId"]))
                        {
                            $subId = $_GET["subCategId"];
                        }

                        while($resAllCateg = mysqli_fetch_assoc($queryAllCateg))
                        {
                            $selected = ($subId == $resAllCateg["id"]) ? "selected" : "";
                            echo '<option value="'.$resAllCateg["id"].'" '.$selected.'>'.$resAllCateg["name"].'</option>';
                        }
                        mysqli_free_result($queryAllCateg);
                    ?>
                </select>            
            </span>
        </span>
        <input class="search-btn" type="submit" value="Search">
    </form>   