<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

    $title = "Account Settings";

    $userOldPass = $userNewPass = $errorOPMsg = "";

    if(!$user["userOk"])
    {
        logout();
        header("Location: index.php");
        exit();
    }

    if(isset($_POST["Save"]))
    {
        $userFname   = mysqli_real_escape_string($dbConx, $_POST["userFname"]);
        $userLname   = mysqli_real_escape_string($dbConx, $_POST["userLname"]);
        $userPhone   = mysqli_real_escape_string($dbConx, $_POST["userPhone"]);
        $userOldPass = mysqli_real_escape_string($dbConx, $_POST["userOldPass"]);
        $userNewPass = mysqli_real_escape_string($dbConx, $_POST["userNewPass"]);

        if(!empty($userFname) && !empty($userLname) && !empty($userPhone) && is_numeric($userPhone))
        {
            $sqlUpdate = 'UPDATE users SET first = "'.$userFname.'", 
                                           last  = "'.$userLname.'",
                                           phone = "'.$userPhone.'"
                          WHERE users.email = "'.$userEmail.'"';

            $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

            if($queryUpdate)
            {
                $_SESSION["userFname"] = $userFname;
                $_SESSION["userLname"] = $userLname;
                $_SESSION["userPhone"] = $userPhone;
            }
        }

        if(!empty($userOldPass) && !empty($userNewPass) && strlen($userNewPass) > 4)
        {
            $sqlFetchPass = 'SELECT COUNT(*) AS rowNbr, passHash FROM users WHERE email = "'.$userEmail.'"';

            $queryFetchPass = mysqli_query($dbConx, $sqlFetchPass);

            $resFetchPass = mysqli_fetch_assoc($queryFetchPass);
            
            if($resFetchPass["rowNbr"] == 1)
            {
                if(password_verify($userOldPass, $resFetchPass["passHash"]))
                {
                    $newPass = password_hash($userNewPass, PASSWORD_DEFAULT);

                    $sqlUpdatePass = 'UPDATE users SET passHash = "'.$newPass.'" WHERE users.email = "'.$userEmail.'"';

                    $queryUpdate = mysqli_query($dbConx, $sqlUpdatePass);
                }
                else
                {
                    $errorOPMsg = "Wrong password, please try again";
                }

                mysqli_free_result($queryFetchPass);
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
        <a href="activity.php" class="profile-nav-item">Activity</a>
    </li>
</ul>

<h3 class="profile-title">Personal Info</h3>

<form class="pesonal-info-container" id="PersonalForm" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
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

    <div id="dp1" class="input-container">
        <input id="ip1" type="password" name="userOldPass" autocomplete="off" placeholder=" " value=""/>
        <label id="lp1" for="ip1" class="label-name">
            <span id="sp1" class="content-name">Old Password</span>
        </label>
    </div>
    <div class="error-msg-container">
        <span id="errorPass1"><?php echo $errorOPMsg?></span>
    </div>

    <div id="dp2" class="input-container">
        <input id="ip2" type="password" name="userNewPass" autocomplete="off" placeholder=" " value=""/>
        <label id="lp2" for="ip2" class="label-name">
            <span id="sp2" class="content-name">New Password</span>
        </label>
    </div>
    <div class="error-msg-container">
        <span id="errorPass2"></span>
    </div>
    <div style="text-align: center;">
        <input type="submit" class="button" name="Save" value="SAVE">
    </div>

</form>


<!-- CLOSING THE CONTAINER, BODY, AND HTML TAGS -->    
</div>
    </body>
</html>