<?php if(!isset($_SESSION["userEmail"])) session_start(); require_once "pdo.php";

function getContribution() {
  $user = getUser($_SESSION["userEmail"]);
  $json = json_decode($user['game_contribution'],true);
  $type = gettype($json);
  if ($type == "array") {
    return $json;
  }
}

function addTeam(string $team) {
  $user = getUser($_SESSION["userEmail"]);
  $pdo = $GLOBALS["pdo"];
  if($user) {
    $json = $user['game_contribution'];
    if ($json != "{\"\"}") {
      $json = json_decode($json,true);
    } else {
      $json = [];
    }
    $json[$team] = [];
    $json = json_encode($json);
    try {
      $sql = $pdo->prepare("UPDATE `user` SET `game_contribution`=:1 WHERE `ID`=:2;");
      $sql->bindParam(":1", $json);
      $sql->bindParam(":2", $_SESSION['userID']);
      $sql->execute();
    } catch (Exception $e) {
      echo $e;
    }
  }
}

function addContibution(string $team, int $game, int $goals = 0, int $assists = 0) {
  $user = getUser($_SESSION["userEmail"]);
  $pdo = $GLOBALS["pdo"];
  if($user) {
    $row = ["goals" => $goals, "assists" => $assists];
    $json = json_decode($user['game_contribution'],true);
    $json[$team][$game] = $row;
    $json = json_encode($json);
    try {
      $sql = $pdo->prepare("UPDATE `user` SET `game_contribution`=:1 WHERE `ID`=:2;");
      $sql->bindParam(":1", $json);
      $sql->bindParam(":2", $_SESSION['userID']);
      $sql->execute();
    } catch (Exception $e) {
      echo $e;
    }
  }
}

if (isset($_POST['newTeam'])) {
  $team = str_replace(' ', '', $_POST['team']);
  addTeam($team); 
  ?> <script>location.href='../pages/stats.php'</script> <?php
}

if (isset($_POST['newGame'])) {
  addContibution($_POST['team'], $_POST["gameID"], $_POST["goals"], $_POST["assists"]);
  ?> <script>location.href='../pages/stats.php'</script> <?php
}