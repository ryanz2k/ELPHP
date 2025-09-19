<?php
session_start();
?>

<!DOCTYPE html>
<html>
     <body>
        <form action="process.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="Login">
        </form>
     </body>
</html>