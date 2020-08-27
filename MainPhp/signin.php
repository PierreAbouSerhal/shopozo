<?php
    session_set_cookie_params(0, "/", "localhost", false, true);
    session_start();
    
    if(isset($_SESSION["userToken"]) || isset($_COOKIE["userToken"]))
    {
        header("Location: index.php");
        exit();
    }

    // WHEN ACCOUNT IS VERIFIED
    if(isset($_GET["activated"]))
    {
        if($_GET["activated"] == 1)
        {
            $msg = "Your account has been verified, please login";
            $status = "success";
        }
    }

    $title     = "Signin";
    $rightInfo = "Shop with confidence"; 

    $userEmail = $userPass = $msg = $status = "";

    if(isset($_POST["signin"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/SHOPOZO/PhpUtils/dbConx.php");
       
        $userEmail = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);
        $userPass  = mysqli_real_escape_string($dbConx, $_POST["userPass"]);
        $isValid   = false;
        $rmbrMe    = (empty($_POST["rememberMe"])) ? false : true;

        //FORM DATA ERROR HANDLING
        if(!empty($userEmail) && !empty($userPass) && strpos($userEmail, '@') !== false)
        {
            $sql   = "SELECT *, COUNT(*) AS rowNbr FROM users WHERE email = '".$userEmail."';";

            $query = mysqli_query($dbConx, $sql);

            $res   = mysqli_fetch_assoc($query);

            mysqli_free_result($query);

            if($res["rowNbr"] == 1)
            {
                $isValid = password_verify($userPass, $res["passHash"]);
            }

            if($isValid)
            {
                //CREATE THE USER TOKEN
                $token = random_bytes(16);
                $token = bin2hex($token);

                //HASH THE USER TOKEN
                $tokenHash = hash("sha256",$token);

                //DELETE TOKEN IF EXISTS
                $sqlDelete = "DELETE FROM userTokens WHERE userId = ".$res["id"];

                $queryDelete = mysqli_query($dbConx, $sqlDelete);

                //INSERT THE HASHED TOKEN INTO THE DATABASE
                $sqlInsert = "INSERT INTO userTokens (userId, hashedToken, creationDate, creationTime)
                                VALUES (".$res["id"].", '".$tokenHash."', CURDATE(), CURTIME()) ;";

                $queryInsert = mysqli_query($dbConx, $sqlInsert);

                if($queryInsert)
                {
                    //CREATE THE SESSIONS 
                    $_SESSION["loggedin"]  = true;
                    $_SESSION["userFname"] = $res["first"];
                    $_SESSION["userLname"] = $res["last"];
                    $_SESSION["userName"]  = $res["first"].' '.$res["last"];
                    $_SESSION["userEmail"] = $res["email"];
                    $_SESSION["isAdmin"]   = ($res["role"] == "ADMIN") ? true : false ;
                    $_SESSION["userToken"] = $token;

                    if($rmbrMe)
                    {
                        //CREATE THE COOKIE IF REMEMBER ME IS CHECKED 
                        setcookie("userToken", $token, time() + ( 100 * 30 * 24 * 60 * 60 ), "/", "localhost", false, true);
                    }
                    
                    header("Location: index.php");
                    exit();
                }
                else
                {
                    $msg    = "Failed to signin, please try again later";
                    $status = "danger";
                }
            }
            else
            {   
                $msg = "Wrong email or password, please try again.";
                $status = "danger";
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
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
                    <div class="input-container">
                        <input type="email" id="userEmail" name="userEmail" autocomplete="off" placeholder=" " value="<?php echo $userEmail?>"/>
                        <label for="userEmail" class="label-name">
                            <span class="content-name">Email address</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="userPass" name="userPass" autocomplete="off" placeholder=" " value="<?php $userPass?>"/>
                        <label for="userPass" class="label-name">
                            <span class="content-name">Password</span>
                        </label>
                    </div>
                    <a href="forgotPass.php" class="forgot-pass">Forgot password?</a>
                    <div class="remember-me-container">
                        <input type="checkbox" name="rememberMe" value="rememberMe">
                        <span class="remember-me">Remember me</span>
                    </div>
                    <div class="btn-and-bottom-link">
                        <input type="submit" class="button" name="signin" value="LOGIN">
                        <p class="bottom-link">Don't have an account ?<a href="register.php" style="color: #6EBE47;">Signup</a></p>
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