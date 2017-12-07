<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/../../config/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../../config'))->load();

$user = getUserEmail(request()->get('email'));

if(empty($user) || !password_verify(request()->get('password'), $user['password'])){
    redirect('/login.php');
} else {

// The iss is the URL that issues the web token, The subject is the subject of the web token so in this case it will be the users ID stored in a JSON object
// The exp is the time when the token expires.
    login(time() + 3600);

}

?>