<?php require_once "pdo.php";

function getTeams() {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT * FROM `team` ORDER BY `team_id` ASC";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function createTeam(string $name) {
  $pdo = $GLOBALS["pdo"];
  $name = str_replace(" ","_",$name);
  $sql = "INSERT IGNORE INTO `team` (`name`)
          VALUES (?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$name]);
}

function getMembers(int $team) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user`.`name`,`user`.`user_id` FROM `user` WHERE `user`.`user_id` IN (SELECT `mtm_user_team`.`user_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`team_id` = $team AND `mtm_user_team`.`active` = true);";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getPotentialMembers() {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user`.`user_id`,`user`.`name` FROM `user` WHERE `user`.`user_id` NOT IN (SELECT DISTINCT `mtm_user_team`.`user_id` FROM `mtm_user_team` WHERE `mtm_user_team`.`active` = true);";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function addMember(int $team, int $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "INSERT INTO `mtm_user_team` (`user_id`, `team_id`)
          VALUES (?, ?)
          ON DUPLICATE KEY UPDATE `active` = true;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user, $team]);
}

function removeMember(int $team, int $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "UPDATE `mtm_user_team`
          SET `active` = false
          WHERE `user_id` = ? AND `team_id` = ?;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user, $team]);
}

function getNextGameIDForTeam(int $team) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT MAX(`game_id`)+1 AS 'game' FROM `goals` WHERE `team_id` = $team;";
  return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

function setGameResults(int $game, int $team, array $players) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];

  try {
    foreach ($players as $key => $player) {
      $sql = "INSERT INTO `goals` (`game_id`, `team_id`, `user_id`, `goal_amount`, `assists`)
          VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `goal_amount` = VALUE(`goal_amount`), `assists` = VALUE(`assists`);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$game, $team, $key, $player["goals"], $player["assists"]]);
    }
  } catch (Exception $e) {
    echo $e;
    return;
  }
  $messages[count($messages)] = "Resultaten zijn erin gezet";
  return;
}

if (isset($_POST["createTeam"])) {
  createTeam($_POST["name"]);
  ?> <script>location.href='../pages/admin.php'</script> <?php
}

if (isset($_POST["addMember"])) {
  addMember($_POST["team_id"],$_POST["member"]);
  ?> <script>location.href='../pages/admin.php'</script> <?php
}

if (isset($_POST["removeMembers"])) {
  if (isset($_POST["player"])) {
    foreach ($_POST["player"] as $key => $user_id) {
      removeMember($_POST["team"],$user_id);
    }
  }
  ?> <script>location.href='../pages/admin.php'</script> <?php 
}

if (isset($_POST["setGameResults"])) {
  setGameResults($_POST["game"], $_POST["team"], $_POST["users"]);
  ?> <script>location.href='../pages/admin.php'</script> <?php 
}