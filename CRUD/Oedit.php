<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/Ops.php';
require_once __DIR__ . '/../Operations/connection.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();

$redirectTo = '/bus.php';

try {
    editBus(request()->get('id'), request()->get('title'), request()->get('description'));
} catch (\Exception $e) {
    $redirectTo = '/edit.php?id='. request()->get('id');
}

\Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => $redirectTo])->send();
