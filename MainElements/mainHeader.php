<?php
    //QUERY ALL CATEGORIES
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../MainCss/mainHeader.css">
    <link rel="stylesheet" href="../MainCss/sideNav.css">
    <script src="../MainJs/sideNav.js"></script>

    <title><?php echo $title?></title>
</head>
<body>
    <!-- INDEPENDENT FROM MAIN CONTAINER -->
    <div class="header-bottom-line others">
        <div class="header-container container">
            <div class="header-left-corner">
                <span class="links">
                    <!-- WHEN LOGGED IN SHOW USER NAME -->
                    <span>Hi!</span>
                    <a href="#">Signin</a>
                    <span>or</span>
                    <a href="#">register</a>
                </span>
            </div>
            <div class="header-right-corner">
                <a href="#">My Profile</a>
                <a href="#">Watch List</a>
                <img class="cart-img" src="../ShopozoPics/shopping-cart.svg" style="margin-left: 10%;">
            </div>
        </div>
    </div>

<div class="container">

    <!-- Mobile header -->

    <div id="mySidenav" class="sidenav mobile">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <!-- NOTE: TO FILL LINKS WITH PHP FFROM DB CATEGORIES -->
        <!-- NOTE: THE CURRENT LINKS ARE EXAMPLES -->
        <span>Cartegories</span>
        <a href="#">Electronics</a>
        <a href="#">Fashion</a>
        <a href="#">Motors</a>
        <a href="#">Sports</a>
        <a href="#">Under 10$</a>
        <a href="#">All Categories</a>
    </div>

     <div class="mobile-header-container mobile">
        <div class="mobile-logo-container">
            <img class="mobile-logo-img" src="../ShopozoPics/shopozo-logo.png" alt="shopozo">
        </div>
        <div class="mobile-menu-header">
            <img class="menu-img" src="../ShopozoPics/user.svg" alt="profile">
            <img class="menu-img" src="../ShopozoPics/emptyheart.svg" alt="saved">
            <img class="menu-img" src="../ShopozoPics/watch-list.svg" alt="watch-list">
            <img class="menu-img" src="../ShopozoPics/shopping-cart.svg" alt="cart">
            <img class="menu-img" src="../ShopozoPics/hamberger.svg" alt="menu" onclick="openNav()">
        </div>
     </div>
     
     <form class="mobile-search-bar-container mobile">
        <div class="mobile-search-wrapper">
            <input class="mobile-search-input" type="text" name="userSearch" placeholder="Search for anything">
        </div>
        <input type="submit" class="mobile-search-btn" value="">
     </form>

    <!-- Normal header -->

    <form class="search-bar-container others">
        <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="Shopozo">
        <span class="search-bar">
            <span class="search-bar-input">
                <span class="search-icon"></span>
                <input type="text" placeholder="Search for anything">
            </span>
            <span class="categories-combo-box-container">
                <select class="combo-box" name="categorie" id="categ">
                    <!-- SELECT TAG MUST BE FILLED FROM DB -->
                    <option value="volvo">Volvo</option>
                    <option value="saab">Saab</option>
                    <option value="opel">Opel</option>
                    <option value="audi">Audi</option>
                </select>            
            </span>
        </span>
        <input class="search-btn" type="submit" value="Search">
    </form>   
    <div class="nav-bar others">
        <ul class="nav-var-ul">
            <li>
                <a href="#">Home</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
            <!-- CATEGORIES NEED TO BE ACCESSEBLE FROM DB -->
            <li>
                <a href="#">Saved</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
            <li>
                <a href="#">Saved</a>
            </li>
        </ul>
    </div>  

