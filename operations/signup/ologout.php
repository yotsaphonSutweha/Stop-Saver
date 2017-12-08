<?php
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../../config/helpers.php';
    require_once __DIR__ . '/../../config/database.php';
    
    (new Dotenv\Dotenv(__DIR__ . '/../../config'))->load();
    
    $accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired", time()-(60 * 60 * 24 * 30), '/', getenv('DOMAIN'));
    redirect('/login.php', ['cookies' => [$accessToken]]);

?>