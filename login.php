<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

(new Dotenv\Dotenv(__DIR__ . '/config'))->load();
$page_title = "Login";
$page_name = "Login";
include __DIR__ . "/inc/header.php";
include __DIR__ . "/inc/footer.php";
?>


<div class="container">
  <div class="col s12 m6 offset-m3 l4 offset-l4  card-panel">
    <form method="post" action="/operations/signup/ologin.php">
      <label>Email</label>
      <input type="email" name="email" placeholder="Email address" required autofocus>
      <br>
      <label>Password</label>
      <input type="password" name="password" placeholder="Password" required>
      <br>
      <button class="btn waves-effect waves-light" type="submit" name="action">
        Login
        <i class="material-icons right">send</i>
      </button>
    </form>
</div>
