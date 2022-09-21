<?php require_once "pdo.php";

function createTeam(string $name) {
  $pdo = $GLOBALS["pdo"];
  $sql = "INSERT IGNORE INTO `team` (`name`)
          VALUES (?);";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$name]);
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
  $sql = "UPDATE `mtm_user_team` (`user_id`, `team_id`)
          SET `active` = false
          WHERE `user_id` = ? AND `team_id` = ?;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user, $team]);
}

function setGameResults(int $game, int $team, array $playerContributions) {
  $pdo = $GLOBALS["pdo"];
  $messages = $GLOBALS["messages"];

  try {
    $sql = "SELECT `user_id` FROM `mtm_user_team` WHERE `team_id` = $team AND `active` = true;";
    $players = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($players as $key => $player) {
      if(isset($playerContributions[$player])) {
        $goals = $playerContributions[$player]["goals"];
        $assists = $playerContributions[$player]["assists"];
      } else {
        $goals = 0;
        $assists = 0;
      }
      $sql = "INSERT IGNORE INTO `goals` (`game_id`, `team_id`, `user_id`, `goals`, `assists`)
          VALUES (?, ?, ?, ?, ?);";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$game, $team, $player, $goals, $assists]);
    }
  } catch (Exception $e) {
    $messages[count($messages)] = $e;
    return;
  }
  $messages[count($messages)] = "Resultaten zijn erin gezet";
  return;
}