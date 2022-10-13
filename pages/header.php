<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/styles/app.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js" defer="true" async="true" crossorigin="anonymous"></script>
  <script src="https://www.google.com/recaptcha/api.js" defer="true" async="true"></script>
  <title>StatTrackers</title>
</head>
<body>
<script src="../assets/app.js"></script>
<?php session_start(); require_once "../assets/pdo.php";
  include_once "./nav.php";
  $_SESSION['messages'] = [];
  if(isset($_SESSION['messages'])) foreach ($_SESSION['messages'] as $key => $message) {
    array_shift($_SESSION['messages']);
?>
    <div onclick="hide('#message<?php echo $key ?>')" id="message<?php echo $key ?>" class="message <?php echo $message["type"] ?>">
      <p><?php echo $message['content'] ?></p>
    </div>
<?php
  }
?>
<div id="container">