<?php include_once "./header.php"; require_once "../assets/team.php"; require_once "../assets/user.php";?>
<div id="admin">
  <div id="Users" class="showTeamInfo current">
    <?php $c = 0; $users = getAllUsers(); foreach ($users as $key => $user) { if ($user["user_id"] == $_SESSION["userID"]) continue; ?>
      <div id="User<?php echo $key ?>" class="setGameResults <?php if ($c == 0) {echo "current"; $c++;} ?>">
        <p>Email: <?php echo $user['email'] ?></p>
        <a href="./viewUser.php?userID=<?php echo $user['user_id'] ?>">View</a>
      </div>
    <?php } ?>
    <div class="games" style="top:-16px;">
      <h3>User List</h3>
      <?php $c = 0; foreach ($users as $key => $user) { if ($user["user_id"] == $_SESSION["userID"]) continue; ?>
        <h4 id="h4User<?php echo $key ?>" class="setGameResults <?php if ($c == 0) {echo "current"; $c++;} ?>" onclick="showGame('#Users','#User<?php echo $key ?>','#h4User<?php echo $key ?>');"><?php echo $user["name"] ?></h4>
      <?php } ?>
    </div>
  </div>
<?php
  $teams = getTeams();
  $potentialMembers = getPotentialMembers();
  $editContent = [];
?>
  <?php foreach ($teams as $key => $team) {
    $editContent[$team["team_id"]] = [];
    $members = getMembers($team['team_id']);
    $games = getTeamGames($team["team_id"]);
    $total = [];
  ?>
    <div id="<?php echo $team['name'] ?>" class="showTeamInfo">
      <?php if($members || $potentialMembers) { ?>
      <div>
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
      </div>

      <?php
        }
        if($members) {
          $gameID = getNextGameIDForTeam($team['team_id']);
      ?>
      <div id="<?php echo $team['name'] ?>NewGame" class="setGameResults current">
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
              <input type="hidden" name="game" value="<?php echo $gameID ?>">
              <td colspan="3"><input type="submit" name="setGameResults" value="Set Results"></td>
            </tr>
          </table>
        </form>
      </div>
      <?php
        }
        if($games) {
          foreach ($games as $key => $game) {
            $contribs = getTeamGameContributions($game["game_id"],$team["team_id"]);
      ?>
      <div id="<?php echo $team['name'].$game["game_id"] ?>" class="setGameResults">
        <form action="../assets/team.php" method="post">
            <input type="hidden" name="team" value="<?php echo $team["team_id"] ?>">
        <table>
          <caption><b>Alter Game <?php echo $game["game_id"] ?> Results</b></caption>
          <tr>
            <th>Member</th>
            <th>Goals</th>
            <th>Assists</th>
          </tr>
          <?php 
              $total[$game["game_id"]]=[];
              $total[$game["game_id"]]["goals"]=0;
              $total[$game["game_id"]]["assists"]=0;
            foreach ($contribs as $key => $contrib) {
              $member = getMember($contrib["user_id"]);
          ?>
            <tr>
              <td><?php echo $member["name"] ?></td>
              <td><input type="number" min="0" name="users[<?php echo $contrib['user_id']; ?>][goals]" value="<?php echo $contrib['goal_amount']; $total[$game['game_id']]['goals'] += $contrib['goal_amount']; ?>"></td>
              <td><input type="number" min="0" name="users[<?php echo $contrib['user_id']; ?>][assists]" value="<?php echo $contrib["assists"]; $total[$game["game_id"]]['assists'] += $contrib["assists"]; ?>"></td>
            </tr>
          <?php } ?>
          <tr>
            <th>Total</th>
            <th><?php echo $total[$game["game_id"]]['goals'] ?></th>
            <th><?php echo $total[$game["game_id"]]['assists'] ?></th>
          </tr>
          <tr>
            <input type="hidden" name="game" value="<?php echo $game["game_id"] ?>">
            <td colspan="3"><input type="submit" name="setGameResults" value="Set Results"></td>
          </tr>
        </table>
        </form>
      </div>
      <?php } if ($total) { ?>
      <div id="<?php echo $team['name'] ?>Total" class="setGameResults total <?php if(!$members) echo "current" ?>">
        <form action="../assets/team.php" method="post">
        <table>
          <caption><b>Total Game Results</b></caption>
          <tr>
            <th>Game</th>
            <th>Goals</th>
            <th>Assists</th>
          </tr>
          <?php 
            $teamTotal = ["goals"=>0,"assists"=>0];
            foreach ($total as $game => $gameTotal) {
          ?>
            <tr>
              <td><?php echo $game ?></td>
              <td><?php echo $gameTotal["goals"]; $teamTotal['goals'] += $gameTotal['goals']; ?></td>
              <td><?php echo $gameTotal["assists"]; $teamTotal['assists'] += $gameTotal['assists']; ?></td>
            </tr>
          <?php } ?>
          <tr>
            <th>Total</th>
            <th><?php echo $teamTotal['goals'] ?></th>
            <th><?php echo $teamTotal['assists'] ?></th>
          </tr>
        </table>
      </div>
      <?php } } ?>


      <div class="games">
        <h3>Game List</h3>
          <?php if ($members) { ?>
            <h4 id="h4<?php echo $team['name'] ?>NewGame" class="setGameResults current" <?php if(!$games) { ?>style="border-bottom: 2px solid cornsilk;"<?php } ?> onclick="showGame('#<?php echo $team['name'] ?>','#<?php echo $team['name'] ?>NewGame','#h4<?php echo $team['name'] ?>NewGame');">New</h4>
          <?php } if ($games) { ?>
            <h4 id="h4<?php echo $team['name'] ?>Total" class="setGameResults <?php if(!$members) { ?>current<?php } ?>" style="border-bottom: 2px solid cornsilk;" onclick="showGame('#<?php echo $team['name'] ?>','#<?php echo $team['name'] ?>Total','#h4<?php echo $team['name'] ?>Total');">Total</h4>
          <?php
            }
            foreach ($games as $key => $game) {
          ?>
          <h4 id="h4<?php echo $team['name'].$game["game_id"] ?>" class="setGameResults <?php if($key == 1 && !$total && !$games) echo "current" ?>" onclick="showGame('#<?php echo $team['name'] ?>','#<?php echo $team['name'].$game['game_id'] ?>','#h4<?php echo $team['name'].$game['game_id'] ?>');"><?php echo $game["game_id"] ?></h4>
          <?php
              }
          ?>
      </div>
    </div>
  <?php } ?>
  <div id="create" class="showTeamInfo">
    <form action="../assets/team.php" method="post">
      <label for="name">Naam: </label>
      <input type="text" name="name"><br>
      <button name="createTeam" type="submit">Maak nieuwe team</button>
    </form>
  </div>
  <div id="teams">
    <h4 id="h4Users" class="showTeamInfo current" onclick="showTeam('#Users','#h4Users')">Users</h4>
    <h3>Team List</h3>
    <h4 id="h4create" style="border-bottom: 2px solid cornsilk;" class="showTeamInfo" onclick="showTeam('#create','#h4create')">Create Team</h4>
    <?php foreach ($teams as $key => $team) { 
      $members = getMembers($team['team_id']);
      $games = getTeamGames($team["team_id"]);
      if ($members || $potentialMembers || $games) {
    ?>
      <h4 id="h4<?php echo $team['name'] ?>" class="showTeamInfo" onclick="showTeam('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>');"><?php echo str_replace("_"," ",$team['name']) ?></h4>
    <?php } } ?>
  </div>
</div>

<script type="text/javascript">
  function showTeam(team,h4) {
    $('h4.showTeamInfo.current').removeClass('current');
    $('div.showTeamInfo.current').removeClass('current');
    $(h4).addClass('current');
    $(team).addClass('current');
  }
  function showGame(team,game,h4) {
    $(team).find('h4.setGameResults.current').removeClass('current');
    $(team).find('div.setGameResults.current').removeClass('current');
    $(h4).addClass('current');
    $(game).addClass('current');
  }
  document.title = "Admin";
</script>
<?php include_once "./footer.html"; ?>