<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    //PAGE INDEX
    $pageIdx = "";

    if(isset($_GET["idx"]))
    {
        $pageIdx = mysqli_real_escape_string($dbConx, $_GET["idx"]);
    }

    if($pageIdx != "CAT" && $pageIdx != "PRD" && $pageIdx != "SUB" && $pageIdx != "SPC" && $pageIdx != "BRD")
    {
        //REDIRECT TO INFO PAGE
        header("Location: generalInfo.php");
        exit();
    }

    //MANAGE ARRAYS
    $arrPHolder = array("CAT"=>"Categorie",  "PRD"=>"Product",   "SUB"=>"Sub categorie", "SPC"=>"Spec"     ,"BRD"=>"Brand");
    $arrTable   = array("CAT"=>"categories", "PRD"=>"products",  "SUB"=>"subCategories", "SPC"=>"specs"    ,"BRD"=>"brands");
    $arrPage    = array("CAT"=>"categs.php", "PRD"=>"prods.php", "SUB"=>"subCategs.php", "SPC"=>"specs.php","BRD"=>"brands.php");

    $phpSelf = $_SERVER["PHP_SELF"]."?idx=".$pageIdx;
    $placeHolder = "Search ".$arrPHolder[$pageIdx];

    $fop = "true";
    $userInput = "";

    if(isset($_POST["userInput"]) && !empty($_POST["userInput"]))
    {
        $userInput = mysqli_escape_string($dbConx, $_POST["userInput"]);
        $fop = $arrTable[$pageIdx].".name LIKE '%".$userInput."%'";
    }

    $sqlSrch = 'SELECT * 
                FROM '.$arrTable[$pageIdx].'
                WHERE '.$fop.'
                ORDER BY Name';

    $querySrch = mysqli_query($dbConx, $sqlSrch);

    $title = "Manage ".$arrPHolder[$pageIdx];

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/adminHeader.php");

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/searchBar.php");
    
?>
        <div class="table-container">
            <table class="mng-table">
                <thead>
                    <tr class="border-bottom table-header">
                    <th>#</th>
                    <th class="col-name"><?php echo $arrPHolder[$pageIdx];?> Name</th>
                    <th style="text-align: center;">Options</th>
                    </tr>
                </thead>
                <tbody>
        <?php
            $lineId = 1;
            while($resSrch = mysqli_fetch_assoc($querySrch))
            {
                $mngId     = $resSrch["id"];
                $mngName   = $resSrch["name"];
                $backColor = "";

                if($lineId % 2 == 0)
                {
                    $backColor = 'style="background-color: rgb(235, 235, 235);"';
                }

                echo '<tr '.$backColor.' class="border-bottom">
                        <th>'.$lineId.'</th>
                        <td class="col-name">'.$mngName.'</td>
                        <td class="col-option">
                            <a class="edit-icon" href="'.$arrPage[$pageIdx].'?isNew=0&id='.$mngId.'">
                                <i class="far fa-edit" title="Edit '.$arrPHolder[$pageIdx].'"></i>
                            </a>
                            <span class="detete-icon">
                                <i id="mng_'.$mngName.'_'.$mngId.'" class="far fa-trash-alt delete" title="Delete '.$arrPHolder[$pageIdx].'"></i>
                            </span>
                        </td>
                      </tr>';

                $lineId += 1;
            }
        ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        //MESSAGEBOX + REMOVE VARIANT FROM DB AND UPDATE HTML
        $(document).ready(function()
        {
            //DELETE 
            $('.delete').click(function()
            {
                let idx     = "<?php echo $pageIdx?>";
                let el      = this;
                let id      = el.id;
                let splitid = id.split("_");
                let backClr = '#118706';

                
                //ID + NAME
                let mngName = splitid[1];
                let mngId   = splitid[2];
               
                //MESSAGE BOX
                $('<div></div>')
                .appendTo('body')
                .html('<div><h6>' + 'Are you sure you want to delete ' + mngName + '?</h6></div>')
                .dialog({
                    modal: true,
                    title: 'Delete message',
                    zIndex: 10000,
                    autoOpen: true,
                    width: 'auto',
                    dialogClass: "no-close",
                    resizable: false,
                    draggable: false,
                    buttons: 
                    {
                        Yes: function() 
                        {
                            $(el).attr("src", "../ShopozoPics/loading-anim.gif");
                            //AJAX REQUEST
                            $.ajax(
                            {
                                url: 'remove.php',
                                type: 'POST',
                                data: { idx: idx ,id: mngId },
                                success: function(response)
                                {
                                    if(response == 1)
                                    {
                                        //REMOVE VARIANT TR FROM HTML
                                        $(el).closest('tr').css('background','tomato');
                                        $(el).closest('tr').fadeOut(800,function()
                                        {
                                            $(el).remove();
                                        });
                                    }
                                    else
                                    {
                                        alert("Unable to remove " + mngName + "!");
                                    }
                                }
                            });
                            $(this).dialog("close");
                        },
                        No: function() 
                        {
                            $(this).dialog("close");
                        }
                    },
                    close: function(event, ui) 
                    {
                        $(this).remove();
                    }
                }).prev(".ui-dialog-titlebar").css("background",backClr).prev(".ui-dialog-titlebar").css("border", "none");
            });
        });
    </script>
</body>
</html>