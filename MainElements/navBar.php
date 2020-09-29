<div class="nav-bar others">
        <ul class="nav-var-ul">
            <li>
                <a href="../MainPhp/index.php">Home</a>
            </li>
            <li>
                <a href="../MainPhp/savedProds.php">Saved</a>
            </li>
            <?php
                //MOVE POINTER TO BEGENING
                mysqli_data_seek($queryPopularCateg, 0);

                if(isset($queryPopularCateg))
                {
                    while($resPopularCateg = mysqli_fetch_assoc($queryPopularCateg))
                    {
                        echo '<li>
                                <a href="../MainPhp/categories.php?categId='.$resPopularCateg["id"].'&subCategId=-1">'.$resPopularCateg["name"].'</a>
                            </li>';
                    }
                }
            ?>
        </ul>
    </div> 