<?php include_once "./header.php"; require_once "../assets/team.php"; require_once "../assets/user.php";?>
<div id="admin">
<?php
  $teams = getTeams();
  $potentialMembers = getPotentialMembers();
  $editContent = [];
?>
  <?php foreach ($teams as $key => $team) {
    $editContent[$team["team_id"]] = [];
    $members = getMembers($team['team_id']);
  ?>
    <div id="<?php echo $team['name'] ?>" class="showTeamInfo">
      <?php if($members || $potentialMembers) { ?>
      <table class="members">
        <caption><b>Members</b></caption>
        <form action="../assets/team.php" method="post">
          <input type="hidden" name="team" value="<?php echo $team["team_id"] ?>">
        <?php
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
        }
        if($members) {
          $gameID = getNextGameIDForTeam($team['team_id']);
      ?>
      <div class="setGameResults">
        <form action="../assets/team.php" method="post">
          <table>
            <caption><b>Set Game Results</b></caption>
            <tr>
              <th>Player</th>
              <th>Goals</th>
              <th>Assists</th>
            </tr>
            <input type="hidden" name="team" value="<?php echo $team["team_id"] ?>">
            <?php
              $editContent[$team["team_id"]][$gameID] = [];
              foreach ($members as $key => $member) {
                array_push($editContent[$team["team_id"]][$gameID], $member);
            ?>
            <tr>
              <td><?php echo $member['name'] ?></td>
              <td><input type="number" min=0 value=0 name="users[<?php echo $member['user_id'] ?>][goals]"></td>
              <td><input type="number" min=0 value=0 name="users[<?php echo $member['user_id'] ?>][assists]"></td>
            </tr>
            <?php } ?>
            <tr>
              <td><input id="game_id" style="width:fit-content;" type="number" name="game" min="1" value="<?php echo $gameID ?>"></td>
              <td colspan="2"><input type="submit" name="setGameResults" value="Set Results"></td>
            </tr>
          </table>
        </form>
      </div>
      <?php
        }
        $games = getTeamGames($team["team_id"]);
        if($games) {
      ?>
      <div class="games">
        <table>
          <tr>
            <th>Game</th>
            <th>Member</th>
            <th>Goals</th>
            <th>Assists</th>
          </tr>
          <?php
            $Tgoals = 0;
            $Tassists = 0;
            foreach ($games as $key => $game) {
              $editContent[$team["team_id"]][$game["game_id"]] = [];
              $row = 0;
              $contribs = getTeamGameContributions($game["game_id"],$team["team_id"]);
              $goals = 0;
              $assists = 0;
                foreach ($contribs as $key => $contrib) {
                  array_push($editContent[$team["team_id"]][$game["game_id"]],$contrib);
                  $goals += $contrib["goal_amount"];
                  $assists += $contrib["assists"];
          ?>
            <tr onclick="//parseEditContent('#<?php echo $team['name'] ?>',<?php echo $team['team_id'] ?>,<?php echo $game['game_id'] ?>);">
              <?php
                if($row == 0) {
                  $span = count($contribs)+1;
              ?>
              <td style="border-top:2px solid black;" rowspan="<?php echo $span ?>"><?php echo $game["game_id"] ?></td>
              <?php
                }
                $row++;
                $member = getMember($contrib["user_id"]);
              ?>
              <td><?php echo $member["name"] ?></td>
              <td><?php echo $contrib["goal_amount"] ?></td>
              <td><?php echo $contrib["assists"] ?></td>
            </tr>
          <?php
              }
          ?>
          <tr>
            <td><b>Total</b></td>
            <td><b><?php echo $goals; $Tgoals += $goals; ?></b></td>
            <td><b><?php echo $assists; $Tassists += $assists; ?></b></td>
          </tr>
          <?php
            }
          ?>
          <tr style="border-top:2px solid black;">
            <td style="border-top:2px solid black;" colspan="2"><b>Total</b></td>
            <td style="border-top:2px solid black;"><b><?php echo $Tgoals ?></b></td>
            <td style="border-top:2px solid black;"><b><?php echo $Tassists ?></b></td>
          </tr>
        </table>
      </div>
      <?php
        }
      ?>
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
      $games = getTeamGames($team["team_id"]);
      if ($members || $potentialMembers || $games) {
    ?>
      <h4 id="h4<?php echo $team['name'] ?>" class="showTeamInfo" onclick="show('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>');"><?php echo str_replace("_"," ",$team['name']) ?></h4>
    <?php } } ?>
  </div>
</div>

<script type="text/javascript">
  function show(team,h4) {
    $('h4.current').removeClass('current');
    $('div.current').removeClass('current');
    $(h4).addClass('current');
    $(team).addClass('current');
  }
  document.title = "Admin";
  const newGameID = <?php echo $gameID ?>;
  var editContent = JSON.parse(<?php echo json_encode($editContent) ?>);
  function parseEditContent(teamname,team,game) {
    // Doesn't work yet because PHP > JSON > JS array returns 'undefined'
    editContent = window.editContent;
    console.log(editContent);
    let content = editContent[team][game];
    console.log($(`${teamname}`).innerHTML);
  }
</script>
<?php include_once "./footer.html"; ?>