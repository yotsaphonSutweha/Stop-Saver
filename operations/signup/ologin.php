<?php
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../../config/helpers.php';
    require_once __DIR__ . '/../../config/database.php';
    
    (new Dotenv\Dotenv(__DIR__ . '/../../config'))->load();
    
    $user = getUserEmail(request()->get('email'));
    
    if(empty($user) || !password_verify(request()->get('password'), $user['password'])){
        redirect('/login.php');
    } else {
        login(time() + (60 * 60 * 24 * 30));
    }

?>