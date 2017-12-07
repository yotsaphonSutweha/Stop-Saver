<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/helpers.php';
require_once __DIR__ . '/../Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();

$user = getUserEmail(request()->get('email'));

if(empty($user) || !password_verify(request()->get('password'), $user['password'])){
    redirect('/login.php');
} else {
    login(time() + 3600);

}

?>