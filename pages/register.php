<?php include_once "./header.php";
  require_once "../assets/user.php"; ?>

<form action="" method="post">
  <label for="email">Email</label>
  <input type="email" name="email" id="email"><br>
  <label for="name">Gebruikersnaam</label>
  <input type="text" name="name" id="name"><br>
  <label for="password">Wachtwoord</label>
  <input type="password" name="password" id="password"><br>
  <label for="password2">Herhaal wachtwoord</label>
  <input type="password" name="password2" id="password2"><br>
  <input type="submit" name="registreer" value="Registreer">
</form>
<script>
  document.title = "Registreer";
</script>
<?php include_once "./footer.html"; ?>