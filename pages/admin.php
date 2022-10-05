<?php include_once "./header.php"; require_once "../assets/team.php"; require_once "../assets/user.php";?>
<div id="admin">
<?php
  $teams = getTeams();
  $potentialMembers = getPotentialMembers();
?>
  <?php foreach ($teams as $key => $team) { ?>
    <div id="<?php echo $team['name'] ?>" class="showTeamInfo">
      <table class="members">
        <caption><b>Members</b></caption>
        <form action="../assets/team.php" method="post">
          <input type="hidden" name="team" value="<?php echo $team["team_id"] ?>">
        <?php
          $members = getMembers($team['team_id']);
          foreach ($members as $key => $member) {
        ?>
        <tr>
          <td><input min="1" type="checkbox" name="player[<?php echo $key ?>]" id="player<?php echo $member['user_id'] ?>" value="<?php echo $member['user_id'] ?>"></td>
          <td colspan="2"><?php echo $member['name'] ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td <?php if(!$potentialMembers) echo "colspan=\"3\""; ?>><input type="submit" name="removeMembers" value="Delete"></td>
          </form>
        <?php
        if ($potentialMembers) {
        ?>
          <form action="../assets/team.php" method="post">
            <input type="hidden" name="team_id" value="<?php echo $team['team_id']; ?>">
            <td><select name="member">
            <?php foreach ($potentialMembers as $key => $member) { ?>
              <option value="<?php echo $member['user_id'] ?>"><?php echo $member['name'] ?></option>
            <?php } ?></select></td>
            <td><button name="addMember" type="submit">Voeg Toe</button></td>
          </form>
        <?php } ?>
        </tr>
      </table>

      <?php
        if($members) {
          $gameID = getNextGameIDForTeam($team['team_id'])['game'];
      ?>
      <div class="setGameResults">
        <form action="../assets/team.php" method="post">
          <table>
            <caption><b>Set Game (<?php echo $gameID ?>) Results</b></caption>
            <tr>
              <th>Player</th>
              <th>Goals</th>
              <th>Assists</th>
            </tr>
            <input type="hidden" name="team" value="<?php echo $team["team_id"] ?>">
            <?php
              foreach ($members as $key => $member) {
            ?>
            <tr>
              <td><?php echo $member['name'] ?></td>
              <td><input type="number" min=0 value=0 name="users[<?php echo $member['user_id'] ?>][goals]"></td>
              <td><input type="number" min=0 value=0 name="users[<?php echo $member['user_id'] ?>][assists]"></td>
            </tr>
            <?php } ?>
            <tr>
              <input type="hidden" name="game" value="<?php echo $gameID ?>">
              <td colspan="3"><input type="submit" name="setGameResults" value="Set Results"></td>
            </tr>
          </table>
        </form>
      </div>
      <?php } ?>
    </div>
  <?php } ?>
  <div id="create" class="showTeamInfo current">
    <form action="../assets/team.php" method="post">
      <label for="name">Naam: </label>
      <input type="text" name="name"><br>
      <button name="createTeam" type="submit">Maak nieuwe team</button>
    </form>
  </div>
  <div id="teams">
    <h3>Team List</h3>
    <h4 id="h4create" style="border-bottom: 2px solid cornsilk;" class="showTeamInfo current" onclick="show('#create','#h4create')">Create Team</h4>
    <?php foreach ($teams as $key => $team) { 
      $members = getMembers($team['team_id']);
      if ($members || $potentialMembers) {
    ?>
      <h4 id="h4<?php echo $team['name'] ?>" class="showTeamInfo" onclick="show('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>')"><?php echo str_replace("_"," ",$team['name']) ?></h4>
    <?php } } ?>
  </div>
</div>

<script>
  function show(team,h4) {
    $('h4.current').removeClass('current');
    $('div.current').removeClass('current');
    $(h4).addClass('current');
    $(team).addClass('current');
  }


</script>
<?php include_once "./footer.html"; ?>