<?php
  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/config/helpers.php';
  require_once __DIR__ . '/config/database.php';
  
  (new Dotenv\Dotenv(__DIR__ . '/config'))->load();
  $page_title = "Add a New Bus";
  $page_name = "Add";
  include __DIR__ . "/inc/header.php";
  include __DIR__ . "/inc/footer.php";
  requireAuth();

?>

<div class="container">
  <div class="card-panel">
    <form method="post" action="/operations/crud/oadd.php">
      <label>Title</label>
      <input type="text" name="title" placeholder="Bus Details" value="">
      <label>Stop Number</label>
      <input type="text" name="stop_number" placeholder="Stop Number"></textarea>
      <button class="btn waves-effect waves-light" type="submit" name="action">
        Add New Bus
        <i class="material-icons right">send</i>
      </button>
    </form>
  </div>
</div>