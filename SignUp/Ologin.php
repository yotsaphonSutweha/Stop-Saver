<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/Ops.php';
require_once __DIR__ . '/../Operations/connection.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();

$user = findUserByEmail(request()->get('email'));

if(empty($user) || !password_verify(request()->get('password'), $user['password'])){
    redirect('/login.php');
} else {
    $expTime = time() + 3600;


// The iss is the URL that issues the web token, The subject is the subject of the web token so in this case it will be the users ID stored in a JSON object
// The exp is the time when the token expires.
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
                        getenv("KEY"), 'HS256'
                    ), 
                    $expTime, '/',  getenv('DOMAIN')
                )
            ]
        ]
    );

}