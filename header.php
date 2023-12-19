
<header>
    <div class="logo">
        <h1 class="poll">Poll</h1>
        <h2 class="maker">Maker</h2>
    </div>
    <nav class="nav1">
        <a href='index.php' class="myButton1">Home</a>
        <a href="create-poll.php" class="myButton1">Create Poll</a>
    </nav>
    <nav class="nav1">
        
        <?php
                if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']==true){
                    echo '<a href="logout.php" class="myButton">Logout</a>';
                }else{
                    echo '<a href="signup.php" class="myButton">Sign Up</a>';
                    echo '<a href="login.php" class="myButton">Login</a>';
                }
            ?>
    </nav>
</header>