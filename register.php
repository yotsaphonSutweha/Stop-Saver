<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

(new Dotenv\Dotenv(__DIR__ . '/config'))->load();
$page_title = "Register";
$page_name = "Register";
include __DIR__ . "/inc/header.php";
include __DIR__ . "/inc/footer.php";
?>

<div class="container">
    <div class="card-panel">
        <form method="post" action="/operations/signup/oregis.php">
            <label>Email</label>
            <input type="email" name="email" placeholder="Email" required autofocus>
            <br>
            <label>Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <br>
            <button class="btn waves-effect waves-light" type="submit" name="action">
                Register
                <i class="material-icons right">send</i>
            </button>
        </form>
    </div>
</div>