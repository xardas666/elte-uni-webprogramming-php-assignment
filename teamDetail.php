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

if (isVariablePresent('teamId')) {
    $teamId = getVariableValue('teamId');
    
    if (isVariablePresent('deleteText')) {
        deleteComment();
    }

    if (isVariablePresent('deleteMatchId')) {
        deleteMatch();
    }

    echo "<title>" . getData()['teams'][getTeamById($teamId)]['name'] . "</title>";
    echo "<h1>Csapat adatai</h1>";

    echo "<h3>Név: " . getData()['teams'][getTeamById($teamId)]['name'] . "</h3>";
    echo "<h3>Város: " . getData()['teams'][getTeamById($teamId)]['city'] . "</h3>";

    echo "<h2>Meccsek:</h2>";
    echo "<table>";

    if (anonymusEnabled()) {
        echo "<tr><th>Dátum</th><th>Id</th><th>Hazai csapat</th><th>Vendégcsapat csapat</th><th>Hazai</th><th>Vendég</th></tr>";
    } else {
        echo "<tr><th>Dátum</th><th>Id</th><th>Hazai csapat</th><th>Vendégcsapat csapat</th><th>Hazai</th><th>Vendég</th><th>Admin funkció</th></tr>";
    }
    foreach (getData()["matches"] as $match) {
        if ($match['home']['id'] === $teamId || $match['away']['id'] === $teamId) {
            $away_color = colorWinnerLooserTeam($match['home']['score'],$match['away']['score']);
            $home_color = colorWinnerLooserTeam($match['away']['score'],$match['home']['score']);

            echo "<tr>";
            echo "<td>{$match["date"]}</td>";
            echo "<td>{$match["id"]}</td>";
            echo "<td style='color:{$home_color}'>".getTeamNameFromId($match['home']['id'])."</td>";
            echo "<td style='color:{$away_color}'>".getTeamNameFromId($match['away']['id'])."</td>";
            echo "<td>{$match['home']['score']}</td>";
            echo "<td>{$match['away']['score']}</td>";
            if (adminEnabled()) {
                echo "<td><a href='modifyMatch.php?matchId={$match["id"]}&teamId={$teamId}'>Módosítás</a> ";
                echo "<a href='teamDetail.php?teamId={$teamId}&deleteMatchId={$match["id"]}'>Törlés</a></td>";
            }
            echo "</tr>";
        }
    }

    echo "</table>";
    echo "<br>";

    echo "<h2>Felhasználók véleményei:</h2>";
    echo "<table>";
    if (anonymusEnabled()) {
        echo "<tr><th>Dátum</th><th>Felhasználó</th><th>Komment</th></tr>";
    } else {
        echo "<tr><th>Dátum</th><th>Felhasználó</th><th>Komment</th><th>Admin funkció</th></tr>";
    }

    if (isset($_POST['new_comment'])) {
        $new_comment = htmlspecialchars($_POST['new_comment']);

        if (!isNotEmpty($new_comment)) {
            addError("not_Empty", "Nem lehet üres!");
        } else {

            addComment($new_comment, $teamId);
        }
    }

    if (getTeamById($teamId) !== false) {
        $team = getData()['teams'][getTeamById($teamId)];
        $found_comments = array_filter(getData()['comments'], function ($comment) use ($team) {
            return $comment['teamid'] === $team['id'];
        });

        foreach ($found_comments as $comment) {
            echo "<tr>";
            echo "<td>{$comment['date']}</td>";
            echo "<td>{$comment['author']}</td>";
            echo "<td>{$comment['text']}</td>";
            if (adminEnabled()) {
                echo "<td><a href='teamDetail.php?teamId={$team['id']}&deleteText={$comment['text']}'>Törlés</a></td>";
            }
            echo "</tr>";
        }

        if (anonymusDisabled()) {
            echo "<tr>";
            echo "<td colspan='3'> ";
            echo "<form action='teamDetail.php?teamId={$teamId}' method='post'>";
            echo "<input type='text' value='' name='new_comment' />";
            echo "<input type='submit'/>";
            checkForError("not_Empty");
            echo "</form>";
            echo "</td> ";
            echo "</tr>";
        }

        echo "</table>";

    }
}


?>

</body>
</html>