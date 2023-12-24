<header>
    <div class="logo">
        <h1 class="poll">Poll</h1>
        <h2 class="maker">Maker</h2>
    </div>
    <span class="burger-icon" onclick="toggleNav()">☰</span>
    <nav class="nav1">
            <a href='index.php' class="myButton1">Home</a>
            <a href="create-poll.php" class="myButton1">Create Poll</a>

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
<script>
function toggleNav() {
    var nav = document.querySelector('.nav1');
    nav.classList.toggle('active');
}
</script>