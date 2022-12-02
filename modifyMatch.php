<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eötvös Loránd Stadion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
include 'common.php';

if (isset($_GET['matchId']) && isset($_GET['teamId'])) {
    $match_id = htmlspecialchars($_GET['matchId']);
    $team_id = htmlspecialchars($_GET['teamId']);
    if (strlen(trim($match_id)) > 0 && adminEnabled()) {

        $match = array_search($match_id, array_column(getData()['matches'], 'id'));

        if(variablePresentAndNotEmpty('date')){
                    modifyMatch($match_id);
        }

        echo "<form action='modifyMatch.php?matchId={$match_id}&teamId={$team_id}' method='post'>";

        echo "<label for='date'><b>Date</b></label>";
        echo "<input type='text' name='date' value='".getData()['matches'][$match]['date']."'>";

        echo "<br>";

        echo " <label for='home_team_id'><b>Home team id</b></label>";
        echo " <input type='text' name='home_team_id' value='". getData()['matches'][$match]['home']['id']."'>";
        echo "<br>";

        echo " <label for='away_team_id'><b>Away team id</b></label>";
        echo " <input type='text' name='away_team_id' value='". getData()['matches'][$match]['away']['id']."'>";
        echo "<br>";

        echo " <label for='home_team_score'><b>Home team score</b></label>";
        echo " <input type='text' name='home_team_score' value='". getData()['matches'][$match]['home']['score']."'>";
        echo "<br>";

        echo " <label for='away_team_score'><b>Away team score</b></label>";
        echo " <input type='text' name='away_team_score' value='". getData()['matches'][$match]['away']['score']."'>";
        echo "<br>";

        echo "<input type='submit'/>";
        echo "</form>";
        echo "<br>";

    } else {
        echo "404";
    }
}


?>


</body>
</html>
