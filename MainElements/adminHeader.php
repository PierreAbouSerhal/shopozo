<?php
        include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");

?>

<link rel="stylesheet" href="../MainCss/mainHeader.css">
<link rel="stylesheet" href="../MainCss/sideNav.css">
<link rel="stylesheet" href="../MainCss/inputFields.css">
<link rel="stylesheet" href="../MainCss/admin.css">
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />

<script src="https://kit.fontawesome.com/3571b2f364.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

<script src="../MainJs/sideNav.js"></script>
<script src="../MainJs/formValidation.js"></script>
<script src="../MainJs/redirect.js"></script>

<title><?php echo $title?></title>
</head>
<body>

<div class="header-bottom-line">
    <div class="header-container container">
        <div class="header-left-corner">
            <span class="links">
                <span>Hi <?php echo $userName.'!'?></span>                        
            </span>
        </div>
        <div class="header-right-corner">
            <a href="../MainPhp/Profile.php">Back to home Page</a>
            <img class="menu-img mobile" src="../ShopozoPics/hamberger.svg" alt="menu" onclick="openNav()">

        </div>
    </div>
</div>

<div class="container">
    <div id="mySidenav" class="sidenav mobile">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <span style="white-space: nowrap;font-size:1.3rem">Manage Shopozo</span>
            
        <a href="../AdminPages/manage.php?idx=PRD">Manage Products</a>
        <a href="../AdminPages/manage.php?idx=CAT">Manage Categories</a>
        <a href="../AdminPages/manage.php?idx=SUB">Manage Sub Categories</a>
        <a href="../AdminPages/manage.php?idx=SPC">Manage Specs</a>
        <a href="../AdminPages/manage.php?idx=BRD">Manage Brands</a>
        
    </div>

    <div class="admin-page-logo-container">
            <img class="admin-logo-img" src="../ShopozoPics/shopozo-logo.png" onclick="redirect('HOM');">
      </div>
