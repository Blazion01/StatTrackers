<nav>
  <ul>
    <li><a href="./home.php">Home</a></li>
    <?php if(!isset($_SESSION["user"])) { ?>
    <li><a href="./login.php">Login</a></li>
    <li><a href="./register.php">Registreer</a></li>
    <?php } else { ?>
      <?php if(isset($_SESSION["userRoles"]) && (in_array("admin",$_SESSION["userRoles"]) || in_array("owner",$_SESSION["userRoles"]))) { ?>
        <li><a href="./admin.php">Admin</a></li>
      <?php } ?>
    <li><a href="./stats.php">Stats</a></li>
    <li><a href="./profile.php">Profiel</a></li>
    <li><a href="./logout.php">Logout</a></li>
    <?php } ?>
  </ul>
</nav>