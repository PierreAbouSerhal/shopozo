<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    $title = "Account Settings";

    if(!$user["userOk"])
    {
        logout();
        header("Location: index/php");
        exit();
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainHeader.php");
?>
<ul class="profile-nav">
    <li class="profile-nav-item-container">
        <a href="profile.php" class="profile-nav-item">Account</a>
    </li>
    <li class="profile-nav-item-container default-profile-item">
        <a aria-current="page" class="profile-nav-item">Activity</a>
    </li>
</ul>