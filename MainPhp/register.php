<?php
    $title = "Register";
    $rightInfo = "DIscover fun, exciting, colorful products for your life."; 

    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/mailSetup.php");

    $userFname = $userLname = $userEmail = $userPass1 = $userPass2 = $msg = $status = "";

    function activationCode ()
    {   
        $rand = random_bytes(16);
        $rand = bin2hex($rand);
        $activCode = password_hash($rand, PASSWORD_DEFAULT);
        return $activCode;
    }

    if(isset($_GET["activated"]) && $_GET["activated"] == 0)
    {
        $msg = "Failed to activate Account, please try again later";
        $status = "danger";
    }

    //INPUT VALIDATION
    if(isset($_POST["register"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");

        $userFname = mysqli_real_escape_string($dbConx, $_POST["userFname"]);
        $userLname = mysqli_real_escape_string($dbConx, $_POST["userLname"]);
        $userEmail = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);
        $userPass1 = mysqli_real_escape_string($dbConx, $_POST["userPass1"]);
        $userPass2 = mysqli_real_escape_string($dbConx, $_POST["userPass2"]);

        if(!empty($userFname) && !empty($userLname) && !empty($userEmail) && !empty($userPass1) && !empty($userPass2))
        {
            if(strpos($userEmail, '@') !== false && strlen($userPass1) > 4 && $userPass1 == $userPass2)
            {
                //UNIQUE ACCOUNT VERIFICATION
                $sqlVerif = "SELECT COUNT(*) AS rowNbr, activated, email FROM users WHERE email = '".$userEmail."' ;";

                $queryVerif = mysqli_query($dbConx, $sqlVerif);
                $res = mysqli_fetch_assoc($queryVerif);
                
                mysqli_free_result($queryVerif);

                $to = array(
                    array(
                        "name" => $userFname.' '.$userLname,
                        "email" => $userEmail
                    )
                );

                //CREATE VERIFICATION CODE
                $activCode = activationCode();

                $subject = "Verification Email";
                $html = '<h1>Hi '.$userFname.' '.$userLname.'</h1><p>Thanks for getting started with Shopozo! Please click on the link below to activate your email address: <a href ="http://localhost/SHOPOZO/MainPhp/activate.php?activCode='.$activCode.'">Activate Account</a></p>
                <p>If you have problems, please paste the above link into your web browser</p>
                <p>Thanks,<p/>
                <p>Shopozo Support</p>';
                $from = array("name" => "Shopozo", "email" => $smtp["username"]);

                if($res["rowNbr"] == 0) //ACCOUNT NOT FOUND
                {
                    //HASH THE PASSWORD 
                    $userPass = password_hash($userPass1, PASSWORD_DEFAULT);
                    
                    //INSERT UNACTIVATED USER
                    $sqlInsert = 'INSERT INTO users (first, last, email, passHash, activationCode, creationDate) 
                                        VALUES ("'.$userFname.'", "'.$userLname.'", "'.$userEmail.'", "'.$userPass.'", "'.$activCode.'", CURDATE())';

                    $queryInsert = mysqli_query($dbConx, $sqlInsert);

                    if($queryInsert)
                    {
                        //SEND THE MAIL
                        $jmomailer = new JMOMailer(true, $smtp);
                        
                        if($jmomailer->mail($to, $subject, $html, $from))
                        {
                            $msg = "Your account has been Created please verify it by clicking the activation link that has been send to your email.";
                            $status = "success";
                        }
                        else
                        {
                            $msg = "An error has occured while sending the mail, please try again";
                            $status ="danger";
                        }
                    }
                }
                else if($res["rowNbr"] == 1 && $res["activated"] == 0)//ACCOUNT FOUND BUT NOT ACTIVATED
                {
                    $sqlUpdate = 'UPDATE users 
                                  SET activationCode = "'.$activCode.'" 
                                  WHERE email = "'.$res["email"].'";';
                    
                    $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

                    if($queryUpdate)
                    {
                        //SEND THE MAIL
                        $jmomailer = new JMOMailer(true, $smtp);
                            
                        if($jmomailer->mail($to, $subject, $html, $from))
                        {
                            $msg = "This account is already created but not activated, please verify it by clicking the activation link that has been sent to your email.";
                            $status = "success";
                        }
                        else
                        {
                            $msg = "An error has occured while sending the mail, please try again";
                            $status ="danger";
                        }
                    }
                }
                else //ACCOUNTS WITH SAME EMAIL ARE FOUND
                {
                    $msg = "An account with the same email address already exists, please try again";
                    $status = "danger";
                }
            }
        }
    }

    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/signinRegisterHeader.php");
?>
        <div class="msg-container">
            <div class="msg <?php echo $status?>"><?php echo $msg;?></div>
        </div>
        <div class="main-container">
            <div class="left">
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>" >
                    <div class="input-container">
                        <input type="text" id="ufn" name="userFname" autocomplete="off" placeholder=" " value="<?php echo $userFname?>"/>
                        <label for="ufn" class="label-name">
                            <span class="content-name">First Name</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="text" id="uln" name="userLname" autocomplete="off" placeholder=" " value="<?php echo $userLname?>"/>
                        <label for="uln" class="label-name">
                            <span class="content-name">Last Name</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="email" id="ue" name="userEmail" autocomplete="off" placeholder=" " value="<?php echo $userEmail?>"/>
                        <label for="ue" class="label-name">
                            <span class="content-name">Email address</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="up1" name="userPass1" autocomplete="off" placeholder=" " value="<?php echo $userPass1?>"/>
                        <label for="up1" class="label-name">
                            <span class="content-name">Password</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="up2" name="userPass2" autocomplete="off" placeholder=" " value="<?php echo $userPass2?>"/>
                        <label for="up2" class="label-name">
                            <span class="content-name">Confirm Password</span>
                        </label>
                    </div>
                    
                    <div class="btn-and-bottom-link">
                        <input type="submit" class="button" name="register" value="REGISTER">
                        <p class="bottom-link">Already have an account ?<a href="signin.php" style="color: #6EBE47;">Login</a></p>
                    </div>
                </form>
            </div>

            <div class="right">
                <div class="right-white-logo">
                    <img class="logo-img" src="../ShopozoPics/White-logo.png" alt="Shopozo">
                </div>
                <div class="right-info">
                    <?php echo $rightInfo?>
                </div>
                <div class="right-img-container">
                    <img class="right-img" src="../ShopozoPics/right-img.png" alt="">
                </div>
            </div>
        </div>


<!-- CLOSING THE CONTAINER, BODY, AND HTML TAGS -->    
        </div>
    </body>
</html>