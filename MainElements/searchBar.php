<form class="form" method="POST" action="<?php echo $phpSelf;?>">
    <span class="search-bar-container" style="margin-bottom: 10px;padding-bottom:20px">
        <input class="search-bar" type="text" placeholder="<?php echo $placeHolder;?>" name="userInput" value="<?php $inpt = (isset($_POST["userInput"])) ? $userInput : "" ; echo $inpt?>">
        <input class="search-icon" type="submit" value="">
    </span>
    <?php
        $href = "";
        switch($pageIdx)
        {
            case "CAT":
                $href = "categs.php";
                break;
            case "PRD":
                $href = "prods.php";
                break;
            case "SUB":
                $href = "subCategs.php";
                break;
            case "SPC":
                $href = "specs.php";
                break;
            case "BRD":
                $href = "brands.php";
                break;
        }    
    ?>
    <div class="mng-button-add-container">
        <a class="mng-button-add" href="<?php echo $href;?>">Add</a>
    </div>
</form>