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

echo "<h1>Eötvös Loránd Stadion</h1>";
echo "<p>Ez az oldal az Eötvös Loránd Stadion csapatainak adatait tartalmazza. Jó szórakozást!</p>";

echo "<h2>Csapatok:</h2>";
echo "<table>";
echo "<tr><th>Id</th><th>Csapat</th><th>Város</th></tr>";
foreach (getData()["teams"] as $team) {
    echo "<tr>";
    echo "<td><a href='teamDetail.php?teamId={$team['id']}'>{$team['id'] }</a></td>";
    echo "<td>{$team["name"]}</td>";
    echo "<td>{$team["city"]}</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";

echo "<h2>Meccsek:</h2>";
echo "<table>";
echo "<tr><th>Dátum</th><th>Id</th><th>Hazai csapat</th><th>Vendégcsapat csapat</th><th>Hazai</th><th>Vendég</th></tr>";
$counter = 1;
$index = 0;
while ($index < count(getData()["matches"]) && maxTableSize() >= $counter) {
    if (isPlayedGame(getData()["matches"][$index]["date"])) {
        $counter++;
        echo "<tr>";
        echo "<td>" . getData()['matches'][$index]['date'] . "</td>";
        echo "<td>" . getData()['matches'][$index]['id'] . "</td>";
        echo "<td>" . getTeamNameFromId(getData()['matches'][$index]['home']['id']) . "</td>";
        echo "<td>" . getTeamNameFromId(getData()['matches'][$index]['away']['id']) . "</td>";
        echo "<td>" . getData()['matches'][$index]['home']['score'] . "</td>";
        echo "<td>" . getData()['matches'][$index]['away']['score'] . "</td>";
        echo "</tr>";
    }
    $index++;
}
echo "</table>";

?>


</body>
</html>

