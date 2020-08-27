<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/HtConfig/dbConfig.php");

    $dbSuccess = false;
    $dbConx = mysqli_connect($db['hostName'], $db['userName'], $db['password']);
    if ($dbConx) 
    {
        $dbSelected = mysqli_select_db($dbConx, $db['dataBase']);
    
        if ($dbSelected) 
        {
            $dbSuccess = true;
        }
    }

    if(!$dbSuccess)
    {
        //CANNOT CONNECT TO MySQL SERVER OR TO DATABE
        header("Location: ../MainPHP/errorPage.php");
        exit();
    }
?>