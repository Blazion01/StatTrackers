<div id="navbar">
  <nav>
    <ul>
      <li><a href="./home.php"><h1>StatTrackers</h1></a></li>
      <?php if(!isset($_SESSION["user"])) { // Only if not logged in ?>
      <li><a href="./login.php">Login</a></li>
      <li><a href="./register.php">Registreer</a></li>
      <?php } else { // Only if logged in ?>
        <?php if(isset($_SESSION["userRoles"]) && (in_array("admin",$_SESSION["userRoles"]) || in_array("owner",$_SESSION["userRoles"]))) { // Only if user has admin privileges ?>
          <li><a href="./admin.php">Admin</a></li>
        <?php } ?>
      <li><a href="./stats.php">Stats</a></li>
      <li><a href="./profile.php">Profiel</a></li>
      <li><a href="./logout.php">Logout</a></li>
      <?php } ?>
    </ul>
  </nav>
</div>
