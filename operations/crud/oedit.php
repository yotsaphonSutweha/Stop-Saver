<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/helpers.php';
require_once __DIR__ . '/../../config/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../../config'))->load();

$redirectTo = '/bus.php';

try {
    editBus(request()->get('id'), request()->get('title'), request()->get('stop_number'));
} catch (\Exception $e) {
    $redirectTo = '/edit.php?id='. request()->get('id');
}

// \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => $redirectTo])->send();
redirect($redirectTo);


?>