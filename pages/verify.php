<?php require_once "../assets/user.php";
$privatekey = "6LcoDHkiAAAAAHHWncTrt7JUQBPOw1oNbrxpkl7t";
// $resp = recaptcha_check_answer($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
$resp = true;
if (!$resp/* ->is_valid */) {?>
  <script>location.href = "../";</script>
<?php 
  $info = "The reCAPTCHA wasn't entered correctly. Go back an try it again.(reCAPTCHAm said: ".$resp->error.")";
  array_push($_SESSION["messages"],$info);
  return;
}

if (isset($_POST["login"])) {
  if (!isset($_SESSION["user"])) {
    $result = getUser($_POST["email"]);
    if($result) {
      if(password_verify($_POST['password'], $result['password'])) {
        $GLOBALS["user"] = $result["name"];
        $GLOBALS["userEmail"] = $_POST["email"];
      } else {
        $info = 'Wachtwoord is niet correct.';
      }
    } else {
      $info = 'Email niet gevonden.';
    }
    array_push($_SESSION['messages'],$info);
  }
}

if (isset($_POST["registreer"])) {
  $messages = $GLOBALS["messages"];
  if ($_POST["password"] == $_POST["password2"]) {
    createUser($_POST["email"],$_POST["name"],$_POST["password"]);
  } else {
    $info = 'Wachtwoorden kwamen niet overeen.';
    $messages[count($messages)] = $info;
  }
}

if (isset($GLOBALS["user"]) && !isset($_SESSION["user"])) {
  $_SESSION["token"] = bin2hex(random_bytes(24));
  $_SESSION["user"] = $GLOBALS["user"];
  $_SESSION["userEmail"] = $GLOBALS["userEmail"];
  $result = getUser($GLOBALS["userEmail"]);
  $_SESSION["userID"] = $result['user_id'];
  $roles = json_decode($result['json'],true)['roles'];
  if($roles) $_SESSION["userRoles"] = $roles;
  ?> <script>location.href="../pages/home.php"</script> <?php
  array_push($_SESSION['messages'],'Logged in as '.$GLOBALS['user']);
}