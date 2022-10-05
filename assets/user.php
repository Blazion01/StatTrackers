<?php require_once "pdo.php";

function getUserJson(string $user) {
  return json_decode(getUser($user)["json"],true);
}

function addRole(string $user, string $role) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];
  try {
    $roles = getUserJson($user)['roles'];
    array_push($roles,$role);
    $roles = json_encode($roles);
    $sql = "UPDATE `user` SET `json` = ? WHERE `email` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($roles, $user);
  } catch (Exception $e) {
    $messages[count($messages)] = $e;
    return;
  }
  $messages[count($messages)] = "Rol $role is toegevoegd bij $user";
  return;
}

function removeRole(string $user, string $role) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];
  try {
    $roles = getUserJson($user)["roles"];
    foreach ($roles as $key => $value) {
      if ($value == $role) {
        array_slice($roles,$key,1);
        break;
      }
    }
    $roles = json_encode($roles);
    $sql = "UPDATE `user` SET `roles` = ? WHERE `email` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($roles, $user);
    $messages[count($messages)] = "Rol $role is verwijderd bij $user";
  } catch (Exception $e) {
    $messages[count($messages)] = $e;
  }
  return $messages;
}

function createUser(string $mail, string $name, string $pass) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];
  $pass = password_hash($pass,1);
  $sql = $pdo->prepare("INSERT INTO `user` (`email`,`name`,`password`) VALUES (?,?,?)");
  try {
    $GLOBALS["user"] = $name;
    $GLOBALS["userEmail"] = $mail;
    $sql->execute([$mail,$name,$pass]);
    $messages[count($messages)] = `User $name created succesfully`;
  } catch (Exception $e) {
    $messages[count($messages)] = $e;
  }
  return;
}

function getCurrentTeam()
{
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `team`.`name`,`team`.`team_id` FROM `team` WHERE `team`.`team_id` IN (SELECT `mtm_user_team`.`team_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`user_id` = $_SESSION[userID] AND `mtm_user_team`.`active` = true);";
  return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

function getTeamMembers(int $team) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user`.`name` FROM `user` WHERE `user`.`user_id` IN (SELECT `mtm_user_team`.`user_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`team_id` = $team AND `mtm_user_team`.`active` = true) AND `user`.`user_id` != $_SESSION[userID];";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getTeamContributions(int $team) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `game_id`,`goal_amount`,`assists` FROM `goals` WHERE `team_id` = $team AND `user_id` = $_SESSION[userID];";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getContributedTeams($team) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `team`.* FROM `team` WHERE `team`.`team_id` IN (SELECT DISTINCT `goals`.`team_id` FROM `goals` WHERE ";
  if(is_int($team)) $sql .= "`goals`.`team_id` != $team AND ";
  $sql .= "`goals`.`user_id` = $_SESSION[userID]) ORDER BY `team`.`team_id` ASC;";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST["bewerk"])) {
  try {
    $sql = $pdo->prepare("UPDATE `user` SET `email`=:1, `name`=:2 WHERE `user_id`=:3;");
    $sql->bindParam(":1", $_POST['email']);
    $sql->bindParam(":2", $_POST['name']);
    $sql->bindParam(":3", $_SESSION['userID']);
    $sql->execute();
    $_SESSION["user"] = $_POST["name"];
    $_SESSION["userEmail"] = $_POST["email"];
  } catch (Exception $e) {
    echo $e;
  }
}

if (isset($_POST["login"])) {
  $messages = $GLOBALS["messages"];
  if (!isset($_SESSION["user"])) {
    $result = getUser($_POST["email"]);
    if($result) {
      if(password_verify($_POST['password'], $result['password'])) {
        $GLOBALS["user"] = $result["name"];
        $GLOBALS["userEmail"] = $_POST["email"];
      } else {
        $info = 'Wachtwoord is niet correct.';
        $messages[count($messages)] = $info;
      }
    } else {
      $info = 'Email niet gevonden.';
      $messages[count($messages)] = $info;
    }
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
  $messages = $GLOBALS["messages"];
  $_SESSION["user"] = $GLOBALS["user"];
  $_SESSION["userEmail"] = $GLOBALS["userEmail"];
  $result = getUser($GLOBALS["userEmail"]);
  $_SESSION["userID"] = $result['user_id'];
  $roles = json_decode($result['json'],true)['roles'];
  if($roles) $_SESSION["userRoles"] = $roles;
  ?> <script>location.href="../pages/home.php"</script> <?php
  $messages[count($messages)] = `Logged in as: $GLOBALS[user]`;
}