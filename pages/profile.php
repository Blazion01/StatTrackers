<?php include_once "./header.php";
  require_once "../assets/user.php"; print_r(json_decode(getUser($_SESSION['userEmail'])['game_contribution'],true)); ?>

<button onclick="bewerkProfiel()">Bewerk</button>
<form action="" method="post">
  <label for="name">Gebruikersnaam: </label>
  <input type="text" name="name" id="name" disabled value="<?php echo $_SESSION['user'] ?>"><br>
  <label for="email">Email: </label>
  <input type="email" name="email" id="email" disabled value="<?php echo $_SESSION['userEmail'] ?>"><br>
  <input type="submit" id="bewerk" name="bewerk" value="Update" disabled>
</form>

<script>
let sw = 0
function bewerkProfiel() {
  switch (sw) {
    case 1:
      $("#name").attr('disabled', true);
      $("#email").attr('disabled', true);
      $("#bewerk").attr('disabled', true);
      sw = 0;
      break;
    default:
      $("#name").attr('disabled', false);
      $("#email").attr('disabled', false);
      $("#bewerk").attr('disabled', false);
      sw = 1;
      break;
  }
}
</script>

<?php include_once "./footer.html"; ?>