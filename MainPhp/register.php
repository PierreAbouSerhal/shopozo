<?php
    $title = "Register";
    $rightInfo = "DIscover fun, exciting, colorful products for your life."; 
    include_once($_SERVER["DOCUMENT_ROOT"]."/Shopozo/MainElements/signinRegisterHeader.php");
?>
        <div class="main-container">
            <div class="left">
                <form>
                    <div class="input-container">
                        <input type="text" id="ufn" name="userFirstName" autocomplete="off" placeholder=" "/>
                        <label for="ufn" class="label-name">
                            <span class="content-name">First Name</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="text" name="userLastName" autocomplete="off" placeholder=" "/>
                        <label for="uln" class="label-name">
                            <span class="content-name">Last Name</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="email" id="ue" name="userEmail" autocomplete="off" placeholder=" "/>
                        <label for="ue" class="label-name">
                            <span class="content-name">Email address</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="up1" name="userPass1" autocomplete="off" placeholder=" "/>
                        <label for="up1" class="label-name">
                            <span class="content-name">Password</span>
                        </label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="up2" name="userPass2" autocomplete="off" placeholder=" "/>
                        <label for="up2" class="label-name">
                            <span class="content-name">Confirm Password</span>
                        </label>
                    </div>
                    
                    <div class="btn-and-bottom-link">
                        <input type="submit" class="button" name="signin" value="REGISTER">
                        <p class="bottom-link">Already have an account ?<a href="#" style="color: #6EBE47;">Login</a></p>
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