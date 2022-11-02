<?php if(!isset($_SESSION)) session_start(); require_once "pdo.php"; require_once "../recaptcha-php-1.11/recaptchalib.php";
  if(!isset($_SESSION['messages'])) $_SESSION['messages'] = [];

// This is to get the users json
// In this case to determine their roles: user, admin, owner, dev
// The order is from least to most privileges
function getUserJson(string $user) {
  return json_decode(getUser($user)["json"],true);
}

// Get all users
// Used in admin page
function getAllUsers() {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user_id`,`name`,`email` FROM `user`";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// This is currently unused but could be used to add a role to a user
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

// This is currently unused but could be used to remove a role from a user
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

// Used to create users
function createUser(string $mail, string $name, string $pass) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];
  $pass = password_hash($pass,1);
  $sql = $pdo->prepare("INSERT INTO `user` (`email`,`name`,`password`) VALUES (?,?,?)");
  try {
    $GLOBALS["user"] = $name;
    $GLOBALS["userEmail"] = $mail;
    $sql->execute([$mail,$name,$pass]);
    array_push($_SESSION['messages'],['type'=>'success','content'=>'User ('.$name.') created']);
  } catch (Exception $e) {
    $sql = "ALTER TABLE `user` AUTO_INCREMENT = 1;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([]);
    array_push($_SESSION['messages'],['type'=>'success','content'=>'Mail ('.$mail.') or Name ('.$name.') already exists']);
  }
  return;
}

// Get the users current team
function getCurrentTeam(int $user)
{
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `team`.`name`,`team`.`team_id` FROM `team` WHERE `team`.`team_id` IN (SELECT `mtm_user_team`.`team_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`user_id` = $user AND `mtm_user_team`.`active` = true);";
  return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

// Get the teammates of the current team
function getTeamMembers(int $team, int $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user`.`name` FROM `user` WHERE `user`.`user_id` IN (SELECT `mtm_user_team`.`user_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`team_id` = $team AND `mtm_user_team`.`active` = true) AND `user`.`user_id` != $user;";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Get the users team contributions
function getTeamContributions(int $team, int $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `game_id`,`goal_amount`,`assists` FROM `goals` WHERE `team_id` = $team AND `user_id` = $user;";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Get all teams where the users is tied to a game
function getContributedTeams($team, int $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `team`.* FROM `team` WHERE `team`.`team_id` IN (SELECT DISTINCT `goals`.`team_id` FROM `goals` WHERE ";
  if(is_int($team)) $sql .= "`goals`.`team_id` != $team AND ";
  $sql .= "`goals`.`user_id` = $user) ORDER BY `team`.`team_id` ASC;";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

// Used to edit the users email and name
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