<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    $title = "Account Settings";

    $userOldPass = $userNewPass = $errorOPMsg = $msg = $status = "";

    if(!$user["userOk"])
    {
        logout();
        header("Location: index.php");
        exit();
    }

    if(isset($_GET["pass"]) && $_GET["pass"] == "changed")
    {
        $msg = "Your password has been successfully changed!";
        $status = "success";
    }

    $selectBorder = (empty($userCountry)) ? "select-default" : "select-selected"; 
    
    $sqlCountrys = 'SELECT * FROM countrys';

    $queryCountrys = mysqli_query($dbConx, $sqlCountrys);

    if(isset($_POST["Save"]))
    {
        $userFname    = mysqli_real_escape_string($dbConx, $_POST["userFname"]);
        $userLname    = mysqli_real_escape_string($dbConx, $_POST["userLname"]);
        $userPhone    = mysqli_real_escape_string($dbConx, $_POST["userPhone"]);
        $userEmail    = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);
        $userCountry  = mysqli_real_escape_string($dbConx, $_POST["userCountry"]);
        $userStreet   = mysqli_real_escape_string($dbConx, $_POST["userStreet"]);
        $userCity     = mysqli_real_escape_string($dbConx, $_POST["userCity"]);
        $userProvince = mysqli_real_escape_string($dbConx, $_POST["userProvince"]);
        $userPostCode = mysqli_real_escape_string($dbConx, $_POST["userPostCode"]);
        // $userOldPass = mysqli_real_escape_string($dbConx, $_POST["userOldPass"]);
        // $userNewPass = mysqli_real_escape_string($dbConx, $_POST["userNewPass"]);

        if(!empty($userFname)   && !empty($userLname)  && !empty($userPhone) && is_numeric($userPhone) && emailIsValid($userEmail) && 
           !empty($userCountry) && !empty($userStreet) && !empty($userCity)  && !empty($userProvince)  && !empty($userPostCode))
        {
            $sqlVerif = 'SELECT COUNT(*) AS rowNbr FROM users WHERE email = "'.$userEmail.'"';

            $queryVerif = mysqli_query($dbConx, $sqlVerif);

            $resVerif = mysqli_fetch_assoc($queryVerif);

            if($resVerif["rowNbr"] == 0 || $userEmail == $_SESSION["userEmail"])
            {
                $sqlUpdate = 'UPDATE users SET first      = "'.$userFname.'", 
                                               last       = "'.$userLname.'",
                                               phone      = "'.$userPhone.'",
                                               email      = "'.$userEmail.'",
                                               countryId  = '.$userCountry.',
                                               street     = "'.$userStreet.'",
                                               city       = "'.$userCity.'",
                                               province   = "'.$userProvince.'",
                                               postalCode = "'.$userPostCode.'"
                              WHERE users.id = '.$user["userId"];

                $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

                if($queryUpdate)
                {
                    $_SESSION["userFname"]    = $userFname;
                    $_SESSION["userLname"]    = $userLname;
                    $_SESSION["userPhone"]    = $userPhone;
                    $_SESSION["userEmail"]    = $userEmail;
                    $_SESSION["userCountry"]  = $userCountry;
                    $_SESSION["userStreet"]   = $userStreet;
                    $_SESSION["userCity"]     = $userCity;
                    $_SESSION["userProvince"] = $userProvince;
                    $_SESSION["userPostCode"] = $userPostCode;

                    $msg = "Your Personal info have been updated!";
                    $status = "success";
                }
                else
                {
                    $msg = "An error has occured while updating your info, please try again later";
                    $status = "danger";
                }
            }
            else
            {
                $msg = "An account already exists with the same email address, please try again";
                $status = "danger";
            }
        }
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/mainHeader.php");
?>
<ul class="profile-nav">
    <li class="profile-nav-item-container default-profile-item">
        <a aria-current="page" class="profile-nav-item">Account</a>
    </li>
    <li class="profile-nav-item-container">
        <a href="history.php" class="profile-nav-item">History</a>
    </li>
</ul>

<div class="msg-container <?php echo $status?>">
    <?php echo $msg?>
</div>

<form class="pesonal-info-container" id="PersonalForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">

    <div class="personal-info-input-container">

        <div class="pesonal-info-left-container" id="PersonalForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
            <h3 class="profile-title">Personal Info</h3>

            <div id="de" class="input-container">
                <input id="ie" type="text" name="userEmail" autocomplete="off" placeholder=" " value="<?php echo $userEmail?>"/>
                <label id="le" for="ie" class="label-name">
                    <span id="se" class="content-name">Email Address</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorEmail"></span>
            </div>

            <div id="dfn" class="input-container">
                <input id="ifn" type="text" name="userFname" autocomplete="off" placeholder=" " value="<?php echo $userFname?>"/>
                <label id="lfn" for="ifn" class="label-name">
                    <span id="sfn" class="content-name">First Name</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorFN"></span>
            </div>

            <div id="dln" class="input-container">
                <input id="iln" type="text" name="userLname" autocomplete="off" placeholder=" " value="<?php echo $userLname?>"/>
                <label id="lln" for="iln" class="label-name">
                    <span id="sln" class="content-name">Last Name</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorLN"></span>
            </div>

            <div id="dph" class="input-container">
                <input id="iph" type="text" name="userPhone" autocomplete="off" placeholder=" " value="<?php echo $userPhone?>"/>
                <label id="lph" for="iph" class="label-name">
                    <span id="sph" class="content-name">Phone Number</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorPhone"></span>
            </div>

            <div id="dp" class="input-container">
                <a href="changePass.php">Click to change account password</a>
            </div>

        </div>

        <div class="pesonal-info-right-container">
            <h3 class="profile-title">Address Info</h3>
        
            <div class="select-container <?php echo $selectBorder?>">
                <div class="select-label">Country</div>
                <span class="select-wrapper">
                    <select id="icou" type="text" name="userCountry" onchange="document.getElementById('icou').sty">
                        <?php

                            if(empty($userCountry))
                            {
                                echo '<option value="" hidden disabled selected value>Choose Country</option>';
                            }

                            while($rowCountry = mysqli_fetch_assoc($queryCountrys))
                            {
                                $selected = "";
                                if($userCountry == $rowCountry["id"])
                                {
                                    $selected = 'selected="selected"';
                                }
                                echo '<option value="'.$rowCountry["id"].'" '.$selected.'>'.$rowCountry["name"].'</option>';
                            }
                            mysqli_free_result($queryCountrys);
                        ?>
                    </select>
                    <img src="../ShopozoPics/down-arrow.svg">
                </span>
            </div>

            <div class="error-msg-container">
                <span id="errorCountry"></span>
            </div>

            <div id="dstr" class="input-container">
                <input id="istr" type="text" name="userStreet" autocomplete="off" placeholder=" " value="<?php echo $userStreet?>"/>
                <label id="lstr" for="istr" class="label-name">
                    <span id="sstr" class="content-name">Street address</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorStreet"></span>
            </div>

            <div id="dcty" class="input-container">
                <input id="icty" type="text" name="userCity" autocomplete="off" placeholder=" " value="<?php echo $userCity?>"/>
                <label id="lcty" for="icty" class="label-name">
                    <span id="scty" class="content-name">City</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorCity"></span>
            </div>

            <div id="dprv" class="input-container">
                <input id="iprv" type="text" name="userProvince" autocomplete="off" placeholder=" " value="<?php echo $userProvince?>"/>
                <label id="lprv" for="iprv" class="label-name">
                    <span id="sprv" class="content-name">State/Province</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorProvince"></span>
            </div>

            <div id="dzip" class="input-container">
                <input id="izip" type="text" name="userPostCode" autocomplete="off" placeholder=" " value="<?php echo $userPostCode?>"/>
                <label id="lzip" for="izip" class="label-name">
                    <span id="szip" class="content-name">Zip Code</span>
                </label>
            </div>
            <div class="error-msg-container">
                <span id="errorZipCode"></span>
            </div>

        </div>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <input type="submit" class="button" name="Save" value="SAVE">
    </div> 
    
</form>

<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/MainElements/mainFooter.html");
?>