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

$email = getVariableValue("email");
$username = getVariableValue("username");
$password1 = getVariableValue("password1");
$password2 = getVariableValue("password2");

if(isNotEmpty($email) || $username || $password1 || $password2){
    addUser();
}

echo "<div>";
echo "<form action='registration.php' method='post'>";
echo "<label for='username'><b>Username</b></label>";
echo "<input type='text' name='username' value='{$username}'>";
checkForError("user_exists");
checkForError("username_cant_be_empty");
echo "<br>";
echo "<label for='email'><b>E-mail</b></label>";
echo "<input type='text' name='email' value='{$email}' >";
checkForError("email_not_properly_formatted");
checkForError("email_cant_be_empty");
echo "<br>";
echo " <label for='password1'><b>Password</b></label>";
echo " <input type='password' name='password1' value='{$password1}'>";
checkForError("password_missmatch");
checkForError("password1_cant_be_empty");
echo "<br>";
echo " <label for='password2'><b>Password</b></label>";
echo " <input type='password' name='password2' value='{$password2}'>";
checkForError("password2_cant_be_empty");
echo "<br>";
echo "<input type='submit'/>";
echo "</form>";
echo "<br>";
echo "</div>";

?>


</body>
</html>
