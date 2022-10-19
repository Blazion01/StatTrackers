<?php include_once "./header.php";
require_once "../assets/user.php";
require_once "../assets/team.php"; ?>
<?php $contrib = false; //print_r($contrib); ?>

<div id="stats">
<?php
  $team = getCurrentTeam($_GET['userID']);
  $teamID = null;
  if(isset($team["team_id"])) $teamID = $team["team_id"];
  $teams = getContributedTeams($teamID, $_GET['userID']);
  if ($team) {
?>
  <div id="<?php echo $team['name']; ?>" class="showTeamInfo current">
    <table class="members">
      <caption>Current Team Members</caption>
      <?php
        $teamMembers = getTeamMembers($team['team_id'], $_GET['userID']);
        if($teamMembers) { foreach ($teamMembers as $key => $teamMember) { 
      ?>
      <tr><td><?php echo $teamMember['name']; ?></td></tr>
      <?php } } else { ?>
      <tr><th>None</th></tr>
      <?php } ?>
    </table>
    <div class="games">
      <table>
        <tr>
          <th>Game</th>
          <th>Goals</th>
          <th>Assists</th>
        </tr>
        <?php
          $contrib = getTeamContributions($team['team_id'], $_GET['userID']);
          $goals = 0;
          $assists = 0;
          foreach ($contrib as $key => $game) {
            $goals += $game['goal_amount'];
            $assists += $game['assists'];
        ?>
        <tr>
          <td><?php echo $game['game_id'] ?></td>
          <td><?php echo $game['goal_amount'] ?></td>
          <td><?php echo $game['assists'] ?></td>
        </tr>
        <?php } ?>
        <tr>
          <th>Total</th>
          <th><?php echo $goals ?></th>
          <th><?php echo $assists ?></th>
        </tr>
      </table>
    </div>
  </div>
<?php
  }
  foreach ($teams as $key => $team) {
?>
<div id="<?php echo $team['name']; ?>" class="showTeamInfo">
  <div class="games">
    <table>
      <tr>
        <th>Game</th>
        <th>Goals</th>
        <th>Assists</th>
      </tr>
      <?php
        $contrib = getTeamContributions($team['team_id'], $_GET['userID']);
        $goals = 0;
        $assists = 0;
        foreach ($contrib as $key => $game) {
          $goals += $game['goal_amount'];
          $assists += $game['assists'];
      ?>
      <tr>
        <td><?php echo $game['game_id'] ?></td>
        <td><?php echo $game['goal_amount'] ?></td>
        <td><?php echo $game['assists'] ?></td>
      </tr>
      <?php } ?>
      <tr>
        <th>Total</th>
        <th><?php echo $goals ?></th>
        <th><?php echo $assists ?></th>
      </tr>
    </table>
  </div>
</div>
<?php } ?>
</div>

<div id="teams">
    <h3>Team List</h3>
<?php
  $team = getCurrentTeam($_GET['userID']);
  if ($team) {
?>
  <h4 id="h4<?php echo $team['name'] ?>" style="border-bottom: 2px solid cornsilk;" class="showTeamInfo current" onclick="show('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>')"><?php echo str_replace("_"," ",$team['name']) ?></h4>
<?php
  }
  foreach ($teams as $key => $team) {
?>
<h4 id="h4<?php echo $team['name'] ?>" class="showTeamInfo" onclick="show('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>')"><?php echo str_replace("_"," ",$team['name']) ?></h4>
<?php
  }
?>
</div>

<script>
  function show(team, h4) {
    $('h4.current').removeClass('current');
    $('div.current').removeClass('current');
    $(h4).addClass('current');
    $(team).addClass('current');
  }
  document.title = "Stats | <?php echo getMember($_GET["userID"])['name'] ?>";
</script>

<?php include_once "./footer.html"; ?>