<?php require_once "pdo.php";

function addMember($team, $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "INSERT INTO `mtm_user_team` (`user_id`, `team_id`)
          VALUES (?, ?)
          ON DUPLICATE KEY UPDATE `active` = true;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user, $team]);
}

function removeMember($team, $user) {
  $pdo = $GLOBALS["pdo"];
  $sql = "UPDATE `mtm_user_team` (`user_id`, `team_id`)
          SET `active` = false
          WHERE `user_id` = ? AND `team_id` = ?;";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$user, $team]);
}