<?php include_once "./header.php";
require_once "../assets/user.php"; ?>
<?php $contrib = false; //print_r($contrib); ?>

<div id="stats"></div>

<div id="teams" class="user" style="text-align:center;">
<?php
  $team = getCurrentTeam();
  if ($team) {
?>
  <h4 id="h4<?php echo $team['name'] ?>" style="border-bottom: 2px solid cornsilk;" class="showTeamInfo" onclick="show('#<?php echo $team['name'] ?>','#h4<?php echo $team['name'] ?>')"><?php echo str_replace("_"," ",$team['name']) ?></h4>
<?php } ?>
<?php if ($contrib) foreach ($contrib as $team => $games) { $goals = 0; $assists = 0; ?>
  <div id="<?php echo $team ?>">
    <h4 onclick="show('#<?php echo $team ?>Contrib')"><?php echo $team ?></h4>
    <div id="<?php echo $team ?>Contrib">
      <table>
        <tr>
          <th>
            GameID
          </th>
          <th>
            Goals
          </th>
          <th>
            Assists
          </th>
        </tr>
        <?php $totalGames = count($games); foreach ($games as $game => $GandA) { ?>
          <tr>
            <td>
              <?php echo $game+1 ?>
            </td>
            <td>
              <?php echo $GandA['goals']; $goals += $GandA['goals']; ?>
            </td>
            <td>
              <?php echo $GandA['assists']; $assists += $GandA['assists']; ?>
            </td>
          </tr>
        <?php } ?>
        <tr>
          <td><b>Total</b></td>
          <td><?php echo $goals ?></td>
          <td><?php echo $assists ?></td>
        </tr>
        <form action="../assets/userContibutions.php" method="post">
          <input type="hidden" name="team" value="<?php echo $team ?>">
          <input type="hidden" name="gameID" value="<?php echo $totalGames ?>">
          <tr>
            <td>
              <input type="submit" name="newGame" value="Voeg Toe">
            </td>
            <td>
              <input type="number" name="goals" value="0">
            </td>
            <td>
              <input type="number" name="assists" value="0">
            </th>
          </tr>
        </form>
      </table>
    </div>
  </div>
<?php } ?>
</div>

<script>
  function show(team, h4) {
    $('h4.current').removeClass('current');
    $('div.current').removeClass('current');
    $(h4).addClass('current');
    $(team).addClass('current');
  }
</script>

<?php include_once "./footer.html"; ?>