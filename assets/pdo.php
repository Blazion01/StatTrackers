<?php
$GLOBALS["messages"] = [];
// Create database connection
$servername = "127.0.0.1:3306";
$dbname = "StatTrackers";
$svname = "mariadb-10.4.24";
$username = "StatTrackers";
$password = "StatTracking";
try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname;serverVersion=$svname", $username, $password);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $GLOBALS["pdo"] = $pdo;
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
// Get user with email
function getUser($email) {
  $pdo = $GLOBALS["pdo"];
  $sql = "SELECT `user_id`,`email`,`json`,`name`,`password` FROM `user` WHERE `email` = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
};