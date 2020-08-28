<?php
    session_set_cookie_params(0, "/", "localhost", false, true);
    session_start();
    
    if(isset($_SESSION["userToken"]) || isset($_COOKIE["userToken"]))
    {
        header("Location: index.php");
        exit();
    }

    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/mailSetup.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");

    $userEmail = $userPin = $userNewPass = $userVerif = "";

    if(isset($_POST["reset"]) && !isset($_SESSION["PIN"]))
    {
        $userEmail = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);

        $_SESSION["userEmail"] = $userEmail;

        if(!empty($userEmail) && strpos($userEmail, '@') !== false)
        {
            $sql = "SELECT *, COUNT(*) AS rowNbr
                    FROM users WHERE email = '".$userEmail."'";

            $query = mysqli_query($dbConx, $sql);

            $row = mysqli_fetch_assoc($query);

            if($row["rowNbr"] == 1)
            {
                $_SESSION["PIN"] = substr(str_shuffle("0123456789"), 0, 4);

                $to = array(
                    array(
                        "name" => $row["first"].' '.$row["last"],
                        "email" => $userEmail
                    )
                );
            
                $subject = "Identity Verification Email";

                $html = '<h1>Hi '.$row["first"].' '.$row["last"].'</h1><p>it\'s seems like you forgot your password. This four digit PIN will verify your identity :</p>
                <p style="font-weight: bold;">'.$_SESSION["PIN"].'</p>
                <p>If you didn\'t reset your password, please ignore this email</p>
                <p>Shopozo Support</>';
                $from = array("name" => "Shopozo", "email" => $smtp["username"]);

                //SEND THE MAIL
                $jmomailer = new JMOMailer(true, $smtp);
                            
                $jmomailer->mail($to, $subject, $html, $from);

            }
        }
    }
    //AFTER SENDING THE MAIL
    $msg       = (!isset($_SESSION["PIN"])) ? "Enter your email address to reset your password" : "A four digit PIN have been sent to your email. Enter your pin to verify your identity";
    $btnName   = (!isset($_SESSION["PIN"])) ? "reset" : "change";
    $btnVal    = (!isset($_SESSION["PIN"])) ? "RESET PASSWORD" : "CHANGE PASSWORD";

    if(isset($_POST["change"]) && isset($_SESSION["PIN"]) && strlen($_SESSION["PIN"]) == 4)
    {
        $pin         = mysqli_real_escape_string($dbConx, $_SESSION["PIN"]);
        $userPin     = mysqli_real_escape_string($dbConx, $_POST["userPin"]);
        $userNewPass = mysqli_real_escape_string($dbConx, $_POST["userNewPass"]);
        $userVerif   = mysqli_real_escape_string($dbConx, $_POST["userVerif"]);
        
        if($pin == $userPin && strlen($userNewPass) > 4 && $userNewPass == $userVerif && isset($_SESSION["userEmail"]))
        {
            $userEmail = mysqli_real_escape_string($dbConx, $_SESSION["userEmail"]);
            $userPass  = password_hash($userNewPass, PASSWORD_DEFAULT);

            $sqlUpdate = "UPDATE users SET passHash = '".$userPass."' WHERE email = '".$userEmail."'";

            $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

            if($queryUpdate)
            {
                $_SESSION = array();
                session_destroy();
                header("Location: signin.php?changed=1");
                exit();
            }
        }
    }
    
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/doctype.html");
?>
<title>Forgot Pass</title>
    <link rel="stylesheet" href="../MainCss/signinRegister.css">
</head>
<body>
        <div class="container">

            <div class="logo-container">
                <img class="logo-img" src="../ShopozoPics/shopozo-logo.png" alt="Shopozo">
            </div>

            <div class="main-container" style="flex-direction: column;">

                <p class="reset-title">Reset Your Password</p>

                <p class="reset-info"><?php echo $msg;?><p>

                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
                    <?php
                        if(!isset($_SESSION["PIN"]))
                        {
                            echo '
                            <div class="input-container" style="margin: 0 5% 0 5%;">
                                <input type="email" id="userEmail" name="userEmail" autocomplete="off" placeholder=" " value="'.$userEmail.'"/>
                                <label for="userEmail" class="label-name">
                                    <span class="content-name">Email address</span>
                                </label>
                            </div>';
                        }
                        else
                        {
                            echo '
                            <div class="input-container" style="margin: 0 5% 0 5%;">
                                <input type="text" id="userPin" name="userPin" autocomplete="off" placeholder=" " value="'.$userPin.'"/>
                                <label for="userPin" class="label-name">
                                    <span class="content-name">Validation PIN</span>
                                </label>
                            </div>
                            
                            <div class="input-container" style="margin: 0 5% 0 5%;">
                                <input type="password" id="userNewPass" name="userNewPass" autocomplete="off" placeholder=" " value="'.$userNewPass.'"/>
                                <label for="userNewPass" class="label-name">
                                    <span class="content-name">New Password</span>
                                </label>
                            </div>

                            <div class="input-container" style="margin: 0 5% 0 5%;">
                                <input type="password" id="userVerif" name="userVerif" autocomplete="off" placeholder=" " value="'.$userVerif.'"/>
                                <label for="userVerif" class="label-name">
                                    <span class="content-name">Verify Password</span>
                                </label>
                            </div>
                            ';
                        }
                    ?>
                    <div class="btn-and-bottom-link">
                        <input type="submit" class="button" name="<?php echo $btnName;?>" value="<?php echo $btnVal;?>">
                    </div>
                </form>
            </div>
       <!-- CLOSING THE CONTAINER, BODY, AND HTML TAGS -->    
       </div>
    </body>
</html>