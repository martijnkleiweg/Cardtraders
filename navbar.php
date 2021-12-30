    <div class="navbar"><?php echo $navigation ?></div>
    <?php
    session_start();
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        session_write_close();
    ?>
        <div class="userbar">Logged in as <a href="home.php"><?php echo $username?></a> <a href="logout.php">(Logout)</a></div>
          <?php
    } else {
        // user is trying to access this page unauthorized
        // so let's clear all session variables and redirect him to login page
        session_unset();
        session_write_close();
    }


     ?>
