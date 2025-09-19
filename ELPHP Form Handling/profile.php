<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <body>
            <?php if (isset($_SESSION['email']) && isset($_SESSION['password'])): ?>
                <p>Email: <?= $_SESSION['email']; ?></p>
                <p>Password: <?= $_SESSION['password']; ?></p>
            </div>
            <?php endif; ?>
        </body>
    </head>
</html>

