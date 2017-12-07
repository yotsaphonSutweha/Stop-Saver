<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Operations/helpers.php';
require_once __DIR__ . '/Operations/database.php';

(new Dotenv\Dotenv(__DIR__ . '/Operations'))->load();
requireAuth();


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
            <li><a href="/add.php">Add a New Bus</a></li>
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
        <h2>List of Journeys</h2>
    
        <?php 
        foreach (getAllBuses() as $bus): ?>
                <h4><?php echo $bus['title']; ?></h4>
                <p> <?php echo $bus['stopno']; ?> </p>
                <p>User ID: <?php echo $bus['user_id']; ?> </p>
                <p> <?php echo $bus['id']; ?> </p>
                <p id=<?php echo $bus['id']; ?>> </p>

                <div id="details" ><script type="text/javascript">
                    $(function() {
                        $.getJSON('https://data.dublinked.ie/cgi-bin/rtpi/realtimebusinformation?stopid='+<?php echo json_encode( $bus['stopno'] ); ?>+'&format=json').done(function(data){
                            console.log(data);
                            if (data.errormessage === ""){
                            for(let j in data.results){
                                $("#"+<?php echo $bus['id']; ?>).append("Stop number " + <?php echo json_encode( $bus['stopno'] ); ?>);
                                $("#"+<?php echo $bus['id']; ?>).append("<p>The route going to the stop is: " + data.results[j].route + "</p>");
                                $("#"+<?php echo $bus['id']; ?>).append("<p>The time that bus is due is: " + data.results[j].duetime + "</p>");
                                $("#"+<?php echo $bus['id']; ?>).append("<p>The departure time of the route: " + data.results[j].departuredatetime + "</p>");
                                $("#"+<?php echo $bus['id']; ?>).append("<p>The routes destination: " + data.results[j].destination + "</p>");
                                $("#"+<?php echo $bus['id']; ?>).append("<p>Any other info about the route: " + data.results[j].additionalinformation + "</p><br/>");
                            }    
                            } else {
                                $("#"+<?php echo $bus['id']; ?>).append("<p>Error this bus is not a vaild bus!!!</p><br/>");
                            }
                            
                        });
                    });
                   
                   
                </script></div>
                <span><a href="/edit.php?id=<?php echo $bus['id'] ?>"> Edit </a></span> |
                <span><a href="/CRUD/Odelete.php?busId=<?php echo $bus['id']; ?>">Delete</a></span>

        <?php endforeach; ?>
        
        
</div>
</body>
</html>