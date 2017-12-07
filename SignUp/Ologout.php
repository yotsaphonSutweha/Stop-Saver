<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/helpers.php';
require_once __DIR__ . '/../Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();


$accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired",
time()-3600, '/', getenv('DOMAIN'));
redirect('/login.php', ['cookies' => [$accessToken]]);

?>