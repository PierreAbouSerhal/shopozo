<?php
    $title = "Signin";
    $rightInfo = "Shop with confidence"; 
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/signinRegisterHeader.php");
?>
        <div class="main-container">
            <div class="left">
                <form>
                    <div class="input-container">
                        <input type="email" name="userEmail" autocomplete="off" placeholder=" "/>
                        <label for="userEmail" class="label-name">
                            <span class="content-name">Email address</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" name="userPass" autocomplete="off" placeholder=" "/>
                        <label for="userPass" class="label-name">
                            <span class="content-name">Password</span>
                        </label>
                    </div>
                    <a href="#" class="forgot-pass">Forgot password?</a>
                    <div class="remember-me-container">
                        <input type="checkbox" name="rememberMe" value="rememberMe">
                        <span class="remember-me">Remember me</span>
                    </div>
                    <div class="btn-and-bottom-link">
                        <input type="submit" class="button" name="signin" value="LOGIN">
                        <p class="bottom-link">Don't have an account ?<a href="#" style="color: #6EBE47;">Signup</a></p>
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