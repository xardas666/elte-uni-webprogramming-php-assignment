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


    $user_found = false;
    $username = getVariableValue('username');
    $password = getVariableValue('password');

    if(!isNotEmpty($username)){
        addError("username_error","Felhasználónév nem maradhat üresen!");
    }
    if(!isNotEmpty($password)){
        addError("password_error","Jelszó nem maradhat üresen!");
    }
    foreach ( getData()["users"] as $user) {
        if ($user["username"] === $username && $user["password"] === $password) {
            $user_found = true;
            break;
        }
    }

    if (!$user_found) {
        addError("login_error","Hibás felhasználónév és/vagy jelszó!");
    } else {
        $_SESSION['username'] = $username;
        header('Location: index.php');
    }


echo "<br>";
echo "<h2>Bejelentkezés</h2>";

checkForError('login_error');
checkForError('username_error');
checkForError('password_error');
echo "<form action='login.php' method='post'>";
echo "<label for='username'><b>Username</b></label>";
echo "<input type='text'name='username'>";
echo "<br>";
echo "<label for='password'><b>Password</b></label>";
echo "<input type='password' name='password'>";
echo "<br>";
echo "<input type='submit'/>";
echo "<br>";
echo "</form>";

?>


</body>
</html>
