<?php include_once "./header.php";
  require_once "../assets/user.php"; // Login with email and password
if (isset($_SESSION["user"])) { ?> <script>location.href="./home.php"</script> <?php }
?>

<form id="login" action="./verify.php" method="post">
  <label for="email">Email</label>
  <input type="email" name="email" id="email" required><br>
  <label for="password">Wachtwoord</label>
  <input type="password" name="password" id="password" required><br>
  <!--<?php $publickey = "6LcoDHkiAAAAAJuIR8rcKJeTAndyEfxzmnnu791b"; echo recaptcha_get_html($publickey) ?>
  <button name="login" class="g-recaptcha" data-sitekey="6LcoDHkiAAAAAJuIR8rcKJeTAndyEfxzmnnu791b" data-callback="onSubmit" data-action="submit">Login</button>-->
  <input name="login"  type="submit" value=Login>
</form>
<script>
  document.title = "Login";
  function onSubmit(token) {
    $('#login').submit();
  }
</script>
<?php include_once "./footer.html"; ?>