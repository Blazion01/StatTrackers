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
  } catch (Exception $e) {
    $messages[count($messages)] = $e;
    return;
  }
  $messages[count($messages)] = "Rol $role is verwijderd bij $user";
  return;
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
  }
}

if (isset($_POST["registreer"])) {
  if ($_POST["password"] == $_POST["password2"]) {
    try {
      $sql = "SELECT `email` FROM `user` WHERE `email` = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$_POST['email']]);
      $result = $stmt->fetch();
      if($result) throw new Exception("Error Email Exists");
      $sql = "SELECT `name` FROM `user` WHERE `name` = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$_POST['name']]);
      $result = $stmt->fetch();
      if($result) throw new Exception("Error Name Exists");
      $sql = $pdo->prepare("INSERT INTO `user` VALUES (null, ?, ?, ?);");
      $hash = password_hash($_POST["password"], 1);
      $GLOBALS["user"] = $_POST["name"];
      $GLOBALS["userEmail"] = $_POST["email"];
      $sql->execute([$_POST["email"], $_POST["name"], $hash]);
    } catch(Exception $e) {
      echo $e->getMessage()." In Database";
    }
  } else {
    $info = 'Wachtwoorden kwamen niet overeen.';
  }
}

if (isset($GLOBALS["user"]) && !isset($_SESSION["user"])) {
  $_SESSION["user"] = $GLOBALS["user"];
  $_SESSION["userEmail"] = $GLOBALS["userEmail"];
  $result = getUser($GLOBALS["userEmail"]);
  $_SESSION["userID"] = $result['user_id'];
  $_SESSION["userRoles"] = getUserJson($GLOBALS["userEmail"])['roles'];
}