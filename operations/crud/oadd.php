<?php
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../../config/helpers.php';
    require_once __DIR__ . '/../../config/database.php';
    
    (new Dotenv\Dotenv(__DIR__ . '/../../config'))->load();
    
    $redirectTo = '/bus.php';
    
    try {
        addJourney(request()->get('title'), request()->get('stop_number'));
    } catch (\Exception $e) {
        $redirectTo = '/add.php';
    }
    
    redirect($redirectTo);
?>