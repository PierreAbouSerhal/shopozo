<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/PhpUtils/checkLoginStatus.php");

$title = "Change Password";

$userOldPass = $userNewPass = $msg = $status = "";

$success = false;

if(!$user["userOk"])
{
    logout();
    header("Location: index.php");
    exit();
}

if(isset($_POST["changePass"]))
{
    $userOldPass = mysqli_real_escape_string($dbConx, $_POST["userOldPass"]);
    $userNewPass = mysqli_real_escape_string($dbConx, $_POST["userNewPass"]);

    if(!empty($userOldPass) && !empty($userNewPass) && strlen($userNewPass) > 4)
    {
        $sqlFetchPass = 'SELECT COUNT(*) AS rowNbr, passHash FROM users WHERE id = '.$user["userId"];

        $queryFetchPass = mysqli_query($dbConx, $sqlFetchPass);

        $resFetchPass = mysqli_fetch_assoc($queryFetchPass);
        
        if($resFetchPass["rowNbr"] == 1)
        {
            if(password_verify($userOldPass, $resFetchPass["passHash"]))
            {
                $newPass = password_hash($userNewPass, PASSWORD_DEFAULT);

                $sqlUpdatePass = 'UPDATE users SET passHash = "'.$newPass.'" WHERE users.id = '.$user["userId"];

                $queryUpdate = mysqli_query($dbConx, $sqlUpdatePass);
                
                if($queryUpdate)
                {
                    $success = true;
                }
                else
                {
                    $msg = "An error has occured while updating your password, please try again later";
                    $status = "danger";

                }
            }
            else
            {
                $msg = "Wrong password, please try again";
                $status = "danger";
            }

            mysqli_free_result($queryFetchPass);
        }
    }

    if($success)
    {
        header("Location: profile.php?pass=changed");
        exit();
    }
}

include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");
?>
<title><?php echo $title?></title>
    <link rel="stylesheet" href="../MainCss/signinRegister.css">
    <link rel="stylesheet" href="../MainCss/profile.css">
    <link rel="stylesheet" href="../MainCss/inputFields.css">

    <script src="../MainJs/formValidation.js"></script>
</head>
<body>

<div class="container">

            <div class="logo-container">
                <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="Shopozo">
            </div>

            <div class="main-container" style="flex-direction: column;">
                <p class="reset-title" style="margin-bottom: 10px;">Change Your Password</p>

                <p class="change-pass-info <?php echo $status?>"><?php echo $msg;?><p>

                <div id="de" class="input-container" style="margin: 0 7% 0 7%;">
                    <input id="ie" type="text" name="userEmail" autocomplete="off" placeholder=" " value="<?php echo $userEmail?>" readonly/>
                    <label id="le" for="ie" class="label-name">
                        <span id="se" class="content-name">Email Address</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorEmail"></span>
                </div>

            <form id="changePassForm" class="left-right-padding" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">

                <div id="dpold" class="input-container">
                    <input id="ipold" type="password" name="userOldPass" autocomplete="off" placeholder=" "/>
                    <label id="lpold" for="ipold" class="label-name">
                        <span id="spold" class="content-name">Old Password</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorOldPass"></span>
                </div>

                <div id="dpnew" class="input-container">
                    <input id="ipnew" type="password" name="userNewPass" autocomplete="off" placeholder=" "/>
                    <label id="lpnew" for="ipnew" class="label-name">
                        <span id="spnew" class="content-name">New Password</span>
                    </label>
                </div>
                <div class="error-msg-container">
                    <span id="errorNewPass"></span>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <input type="submit" class="button" name="changePass" value="CHANGE PASSWORD">
                </div> 

            </form>

            </div>
            
</div>

<!-- CLOSING THE CONTAINER, BODY, AND HTML TAGS -->    
</div>
    </body>
</html>