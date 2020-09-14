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