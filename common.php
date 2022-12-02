<?php
session_start();

$errors = array();
$database = array();

addMenuBar();
createMessagesIfNeccessary();
createErrorsIfNeccessary();

function createMessagesIfNeccessary()
{
    global $messages;
    if (empty($messages)) {
        if (variablePresentAndNotEmpty('messages')) {
            $messages = getVariableValue('messages');
        }
    }
}

function createErrorsIfNeccessary()
{
    global $errors;
    if (empty($errors)) {
        if (variablePresentAndNotEmpty('errors')) {
            $errors = getVariableValue('errors');
        }
    }
}

function addError($key, $message)
{
    global $errors;
    array_push($errors, ['key' => $key, 'message' => $message]);
    $_SESSION['errors'] = $errors;
}

function checkForError($error_key)
{
    global $errors;

    $keys = array_keys($errors);

    foreach ($keys as $key) {
        if ($errors[$key]['key'] === $error_key) {
            echo "<span style='color:red'>{$errors[$key]['message']} </span>";
            unset($errors[$key]);
        }
    }
    $_SESSION['errors'] = $errors;
}

function getData()
{
    global $database;

    if (empty($database)) {
        $database = getDatabase();
    }

    return $database;
}

function getDatabase()
{
    $file = file_get_contents("database.json");
    return json_decode($file, true);
}

function maxTableSize()
{
    return 5;
}

function saveData()
{
    usort(getData()["matches"], "dateCompare");
    usort(getData()["comments"], "dateCompare");
    usort(getData()["users"], "nameCompare");
    usort(getData()["teams"], "idCompare");

    file_put_contents("database.json", json_encode(getData()));
}

function isPlayedGame($date)
{
    return new DateTime($date) < new DateTime();
}

function addComment($comment, $team_id)
{
    global $database;
    array_push($database['comments'], ['date' => date("Y-m-d"), 'author' => getUserName(), 'text' => $comment, 'teamid' => $team_id]);
    saveData();
}

function deleteComment()
{
    global $database;
    getData();
    $text = getVariableValue('deleteText');

    $keys = array_keys($database['comments']);
    foreach ($keys as $key) {
        if ($database['comments'][$key]['text'] === $text) {

            unset($database['comments'][$key]);
            break;
        }
    }

    saveData();

}

function deleteMatch()
{
    global $database;
    getData();
    if (isset($_GET['deleteMatchId'])) {
        $torlendo_match_id = getVariableValue('deleteMatchId');


        $keys = array_keys($database["matches"]);
        foreach ($keys as $key) {
            if ($database["matches"][$key]['id'] === $torlendo_match_id) {
                unset($database['matches'][$key]);
                break;
            }
        }

        saveData();
    }


}

function getUserType()
{
    if (getUserName() !== "ANONYMUS") {
        if (getUserName() === "admin") {
            return "ADMIN";
        } else {
            return getUserName();
        }
    } else {
        return "ANONYMUS";
    }
}

function getUserName()
{
    if (isVariablePresent('username')) {
        return getVariableValue('username');
    } else {
        return "ANONYMUS";
    }
}

function anonymusDisabled()
{
    return getUserType() !== "ANONYMUS";
}

function anonymusEnabled()
{
    return getUserType() === "ANONYMUS";
}

function adminEnabled()
{
    return getUserType() === "ADMIN";
}

function dateCompare($a, $b)
{
    return (strtotime($b["date"]) - strtotime($a["date"]));
}

function idCompare($a, $b)
{
    return strcmp($b["id"], $a["id"]);
}

function nameCompare($a, $b)
{
    return strcmp($b["username"], $a["username"]);
}

function isVariablePresent($key)
{
    if (isset($_GET[$key])) {
        return true;
    } else if (isset($_POST[$key])) {
        return true;
    } else if (isset($_SESSION[$key])) {
        return true;
    } else {
        return false;
    }
}

function getTeamById($team_id)
{
    getData();
    return array_search($team_id, array_column(getData()['teams'], 'id'));
}

function getTeamNameFromId($team_id)
{
    return getData()['teams'][getTeamById($team_id)]['name'];

}

function getVariableValue($key)
{
    if (isset($_GET[$key])) {
        return htmlspecialchars($_GET[$key]);
    } else if (isset($_POST[$key])) {
        return htmlspecialchars($_POST[$key]);
    } else if (isset($_SESSION[$key])) {
        if (gettype($_SESSION[$key]) == "array") {
            return $_SESSION[$key];
        } else {
            return htmlspecialchars($_SESSION[$key]);
        }
    } else {
        return "";
    }
}

function variablePresentAndNotEmpty($key)
{
    return isVariablePresent($key) && isNotEmpty(getVariableValue($key));
}


function isNotEmpty($value)
{
    if (gettype($value) == "array") {
        return count($value) > 0;
    } else {
        return strlen(trim($value)) > 0;
    }

}


function addAlert($message)
{
    echo '<script>alert({$message})</script>';
}

function addMenuBar()
{

    echo "<ul>";
    echo "    <li><a href='index.php'>Főoldal</a></li>";
    if (anonymusEnabled()) {
        echo "<li><a href='login.php'>Belépés</a></li>";
        echo "<li><a href='registration.php'>Regisztráció</a></li>";
    } else {
        echo "<li><a href='logout.php'>Kijelentkezés</a></li>";
    }


    echo "</ul>";
}


function modifyMatch($match_id)
{
    global $database;
    getData();
    $match = array_search($match_id, array_column($database['matches'], 'id'));

    $date = getVariableValue("date");
    $home_team_id = getVariableValue("home_team_id");
    $home_team_score = getVariableValue("home_team_score");
    $away_team_id = getVariableValue("away_team_id");
    $away_team_score = getVariableValue("away_team_score");

    if (strlen(trim($date)) > 0) {
        $keys = array_keys($database['matches']);
        foreach ($keys as $key) {
            if ($database['matches'][$key]['id'] === $match_id) {

                $database['matches'][$match]['date'] = $date;
                $database['matches'][$match]['home']['id'] = $home_team_id;
                $database['matches'][$match]['away']['id'] = $away_team_id;
                $database['matches'][$match]['home']['score'] = $home_team_score;
                $database['matches'][$match]['away']['score'] = $away_team_score;

                saveData();
                $database = getDatabase();
                $team_id = htmlspecialchars($_GET['teamId']);
                header("Location: teamDetail.php?teamId={$team_id}");
                break;

            }
        }
    }
}


function addUser()
{
    global $database;
    getData();
    $email = getVariableValue("email");
    $username = getVariableValue("username");
    $password1 = getVariableValue("password1");
    $password2 = getVariableValue("password2");

    $error = false;
    $empty_error = false;

    if (!isNotEmpty($email)) {
        addError("email_cant_be_empty", "A mező nem maradhat üresen!");
        $empty_error = true;
    }
    if (!isNotEmpty($password1)) {
        addError("password1_cant_be_empty", "A mező nem maradhat üresen!");
        $empty_error = true;
    }
    if (!isNotEmpty($password2)) {
        addError("password2_cant_be_empty", "A mező nem maradhat üresen!");
        $empty_error = true;
    }
    if (!isNotEmpty($username)) {
        addError("username_cant_be_empty", "A mező nem maradhat üresen!");
        $empty_error = true;
    }


    if (!$empty_error && isset($_POST['username'])) {

        if (strlen(trim($username)) > 0) {

            foreach ($database["users"] as $user) {
                if ($user["username"] === $username) {
                    addError("user_exists", "Létező felhasználó!");
                    $error = true;
                    break;
                }
            }
        }
    }

    if (!$empty_error && isset($_POST['password1']) && isset($_POST['password2'])) {

        if (strlen(trim($password1)) > 0 && strlen(trim($password2)) > 0) {
            if ($password1 !== $password2) {
                addError("password_missmatch", "Jelszó nem egyezik!");
                $error = true;
            }
        }
    }

    if (!$empty_error && isset($_POST['email'])) {

        if (strlen(trim($email)) > 0) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                addError("email_not_properly_formatted", "Helytelen Email formátum!");
                $error = true;
            }
        }
    }


    if (!$error) {
        array_push($database['users'], ['username' => $username, 'email' => $email, 'password' => $password1]);
        saveData();
        header('Location: index.php');
    }
}

function colorWinnerLooserTeam($team1, $team2)
{
    if ($team1 > $team2) {
        return "red";
    } else if ($team1 < $team2) {
        return "green";
    } else if (!isNotEmpty($team1) && !isNotEmpty($team2)) {
        return "black";
    } else if ($team1 === $team2) {
        return "yellow";
    }
}

?>