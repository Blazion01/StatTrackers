<?php include_once "./header.php"; require_once "../assets/team.php"; require_once "../assets/user.php";?>
<div id="admin">
<?php
  $teams = getTeams();
?>
  <div id="teams">
  <?php foreach ($teams as $key => $team) { ?>
    <div>
      <h4 onclick="show('#<?php echo $team['name'] ?>')"><?php echo str_replace("_"," ",$team['name']) ?></h4>
      <div id="<?php echo $team['name'] ?>">
        <table class="teamMembers">
          <tr>
            <td colspan="3"><b>Members</b></td>
          </tr>
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
          <?php } $members = getPotentialMembers(); ?>
          <tr>
            <td <?php if(!$members) echo "colspan=\"3\""; ?>><input type="submit" name="removeMembers" value="Delete"></td>
            </form>
          <?php
          if ($members) {
          ?>
            <form action="../assets/team.php" method="post">
              <input type="hidden" name="team_id" value="<?php echo $team['team_id']; ?>">
              <td><select name="member">
              <?php foreach ($members as $key => $member) { ?>
                <option value="<?php echo $member['user_id'] ?>"><?php echo $member['name'] ?></option>
              <?php } ?></select></td>
              <td><button name="addMember" type="submit">Voeg Toe</button></td>
            </form>
          <?php } ?>
          </tr>
        </table>
      </div>

    </div>
  <?php } ?>
    <div>
      <form action="../assets/team.php" method="post">
        <label for="name">Naam: </label>
        <input type="text" name="name"><br>
        <button name="createTeam" type="submit">Maak nieuwe team</button>
      </form>
    </div>
  </div>
</div>

<script>
  function show(team) {
    switch ($(team).css('display')) {
      case 'block':
        $(team).css('display', 'none');
        break;
      case 'none':
        $(team).css('display', 'block');
        break;
    }
  }
</script>
<?php include_once "./footer.html"; ?>