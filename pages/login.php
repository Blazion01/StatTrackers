<?php include_once "./header.php";
  require_once "../assets/user.php";
if (isset($_SESSION["user"])) { ?> <script>location.href="./home.php"</script> <?php }
?>

<form action="" method="post">
  <label for="email">Email</label>
  <input type="email" name="email" id="email"><br>
  <label for="password">Wachtwoord</label>
  <input type="password" name="password" id="password"><br>
  <input type="submit" name="login" value="Login">
</form>

<?php include_once "./footer.html"; ?>