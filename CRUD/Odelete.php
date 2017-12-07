<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Operations/helpers.php';
require_once __DIR__ . '/../Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/../Operations'))->load();



try {
    deleteBus(getBus(request()->get('busId'))['id']);
} catch (\Exception $e) {
    
}
\Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => '/bus.php'])->send();
exit;

?>