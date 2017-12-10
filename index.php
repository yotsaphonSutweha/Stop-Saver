<?php
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/config/helpers.php';
    require_once __DIR__ . '/config/database.php';
    
    (new Dotenv\Dotenv(__DIR__ . '/config'))->load();
    $page_title = "Welcome to Stop Saver";
    $page_subtitle = userAuth() ? "" : "Save and search your favorite stops now!";
    $page_name = "Home";
    
    include __DIR__ . '/inc/header.php';
    include __DIR__ . '/inc/footer.php';
?>

  
<?php if(userAuth()) : ?>
    <div class="center-align">
        <a class="waves-effect waves-light btn-large" href="/add.php"><i class="material-icons">create</i></a>
        <a class="waves-effect waves-light btn-large" href="/bus.php"><i class="material-icons">directions_bus</i></a>
    </div>
<?php endif; ?>
