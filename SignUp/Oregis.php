<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/helpers.php';
require_once __DIR__ . '/../Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();


$pass = request()->get('password');
$confirmPass = request()->get('confirm_password');
$emailAddress = request()->get('email');

$user = getUserEmail($emailAddress);

if($pass != $confirmPass || !empty($user)){
    redirect('/register.php');
}


$hashed = password_hash($pass, PASSWORD_DEFAULT);
$user = createUser($emailAddress, $hashed);

login(time() + 3600);
?>