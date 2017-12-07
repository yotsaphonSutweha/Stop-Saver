<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Operations/helpers.php';
require_once __DIR__ . '/Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/Operations'))->load();

$bus = getBus(request()->get('id'));

?>
<!doctype html>
<html lang="en">
    <head>
        <title>Stop Saver</title>

    </head>
    <body>

        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/bus.php">List of Buses</a></li>
            <li><a href="/Oadd.php">Add a New Bus</a></li>
        </ul>

       <ul>
            <?php if(!userAuth()) : ?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
            <?php else: ?>
            <li><a href="/SignUp/Ologout.php">Logout</a></li>
            <?php endif; ?>
        </ul>


<div>
        <h2>Add Journey</h2>
        
    <form method="post" action="/CRUD/Oedit.php">
        
        <input type="hidden" name="id" value="<?php echo $bus['id']; ?>"/>
        <div>
            <label>Journey</label>
            <input type="text" id="title" name="title" placeholder="Bus Title" value="<?php echo $bus['title']; ?> ">
        </div>

        <div>
            <label>Description</label>
            <input type="text" name="description" placeholder="Description of the bus" value=<?php echo $bus['stopno']; ?> />
        </div>
        
        <div>
            <button type="submit">Update Bus</button>
        </div>
    </form>

</div>

</body>
</html>