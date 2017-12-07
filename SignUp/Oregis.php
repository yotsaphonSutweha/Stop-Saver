<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/Ops.php';
require_once __DIR__ . '/../Operations/connection.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();


$pass = request()->get('password');
$confirmPass = request()->get('confirm_password');
$emailAddress = request()->get('email');

if($pass != $confirmPass){
    // echo 2;
    redirect('/register.php');
}

$user = findUserByEmail($emailAddress);
if(!empty($user)){
    
    redirect('/register.php');
}

$hashed = password_hash($pass, PASSWORD_DEFAULT);
// echo password_hash($password, PASSWORD_DEFAULT);
$user = createUser($emailAddress, $hashed);

$expTime = time() + 3600;

redirect('/',
        [
            'cookies' => [
                new Symfony\Component\HttpFoundation\Cookie('access_token', 
                    \Firebase\JWT\JWT::encode(
                        [
                            'iss' => request()->getBaseUrl(),
                            'user' => findUserByEmail(request()->get('email'))['id'],
                            'exp' => $expTime
                        ], 
                        getenv("SECRET_KEY"), 'HS256'
                    ), 
                    $expTime, '/',  getenv('COOKIE_DOMAIN')
                )
            ]
        ]
    );