<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Operations/helpers.php';
require_once __DIR__ . '/Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/Operations'))->load();

?>

<!doctype html>
<html lang="en">
    <head>
        <title>Stop Saver</title>

    </head>
    <body>

        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/bus.php">List of Buses</a></li>
            <li><a href="/add.php">Add a New Bus</a></li>
        </ul>

       <ul>
            <?php if(!userAuth()) : ?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
            <?php else: ?>
            <li><a href="/SignUp/Ologout.php">Logout</a></li>
            <?php endif; ?>
        </ul>

<div>
        <h2>Stop Saver</h2>
        <p>Welcome to Stop Saver.</p>
</div>
</body>
</html>