<?php
  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/config/helpers.php';
  require_once __DIR__ . '/config/database.php';
  
  (new Dotenv\Dotenv(__DIR__ . '/config'))->load();
  
  $bus = getBus(request()->get('id'));
  $page_title = "Edit";
  $page_name = "Edit";
  include __DIR__ . "/inc/header.php";
  include __DIR__ . "/inc/footer.php";
?>


<div class="container">
  <div class="card-panel">
    <form method="post" action="/operations/crud/oedit.php">
        <input type="hidden" name="id" value="<?php echo $bus['id']; ?>"/>
        <label>Journey</label>
        <input type="text" id="title" name="title" placeholder="Bus Title" value="<?php echo $bus['title']; ?> ">
        <label>Stop Number</label>
        <input type="text" name="stop_number" placeholder="Stop Number" value=<?php echo $bus['stopno']; ?> />
        <button class="btn waves-effect waves-light" type="submit" name="action">
            Update Bus
            <i class="material-icons right">send</i>
        </button>
    </form>
  </div>
</div>
