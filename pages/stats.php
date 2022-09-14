<?php include_once "./header.php";
require_once "../assets/userContibutions.php"; ?>
<?php $contrib = getContribution(); //print_r($contrib); ?>


<div id="teams" style="text-align:center;">
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
  <div>
    <form action="../assets/userContibutions.php" method="post">
      <label for="team">Nieuwe Team</label>
      <input required type="text" name="team" id='newTeam'>
      <input type="submit" name="newTeam" value="Maak Aan">
    </form>
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