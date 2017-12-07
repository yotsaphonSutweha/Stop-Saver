<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Operations/Ops.php';
require_once __DIR__ . '/Operations/connection.php';

(new Dotenv\Dotenv(__DIR__ . '/Operations'))->load();
requireAuth();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://use.fontawesome.com/e175f0cc50.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.2.1.min.js"
      integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
      crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <link rel="stylesheet" href="/css/index.css">
    <link rel="shortcut icon" href="/logo.ico"> 

  </head>
  <body>
    <nav>
      <div class="nav-wrapper teal">
        <div class="container">
          <a href="/" class="brand-logo"><i class="material-icons">directions_bus</i>Stop Saver</a>
          <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
          <ul class="right hide-on-med-and-down">
            <?php if(!isAuthenticated()) : ?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
            <?php else: ?>
            <li><a href="/bus.php">List of Buses</a></li>
            <li><a href="/add.php">Add a New Bus</a></li>
            <li><a href="/SignUp/Ologout.php">Logout</a></li>
            <?php endif; ?>
          </ul>
          <ul class="side-nav" id="mobile-demo">
            <?php if(!isAuthenticated()) : ?>
            <li><a href="/login.php">Login</a></li>
            <li><a href="/register.php">Register</a></li>
            <?php else: ?>
            <li><a href="/bus.php">List of Buses</a></li>
            <li><a href="/add.php">Add a New Bus</a></li>
            <li><a href="/SignUp/Ologout.php">Logout</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <header class="header">
      <div class="container center-align">
        <h1>Journeys</h1>
      </div>
    </header>
    <div class="container">
      
      
          
        <div id="y"></div>

        <div id="x<?php echo $bus['id']?>"></div>
       
 
        
      
        <?php 
        foreach (getAllBuses() as $bus): ?>
 <ul class="collection with-header">
      <li class="collection-header grey lighten-4">
        <h4>

          <?php echo $bus['title']; ?>

          <div class="right">
            <a href="/edit.php?id=<?php echo $bus['id'] ?>" class="btn-floating">
              <i class="material-icons">create</i>
            </a>
            <a href="/CRUD/Odelete.php?busId=<?php echo $bus['id']; ?>" class="btn-floating">
              <i class="material-icons">delete</i>
            </a>
          </div>
        </h4>
      </li>
      <li class="collection-item">
          <h5>
            <strong>
              Incoming Buses to Bus Stop
              <?php echo $bus["stopno"] ?>
            </strong>
          </h5>
        <br>
        <div id="x<?php echo $bus['id']?>"></div>
       
 
        
      

      </li>
    </ul>

                <div id="details" ><script type="text/javascript">
                    stopNumber = <?php echo json_encode( $bus['stopno'] ); ?>;
                    $(function() {
                      
                      
                        $("#x<?php echo $bus['id']?>").append();
                        $.getJSON('https://data.dublinked.ie/cgi-bin/rtpi/realtimebusinformation?stopid='+<?php echo json_encode( $bus['stopno'] ); ?>+'&format=json').done(function(data){
                            console.log(data);
                            if (data.errormessage === ""){
                            for(let j in data.results){
                                $("#x<?php echo $bus['id']?>").append(
                                `<ul class="collection with-header" >
                                    <li class="collection-header grey lighten-4" >
                                        <h5>
                                          <i class="material-icons">directions_bus</i>
                                          <span id="route">${data.results[j].route}</span>
                                        </h5>
                                      </li>
                                      <li class="collection-item" >
                                        <p>
                                          <strong>Due in: </strong>
                                          <span id="due">${data.results[j].route}</span>
                                        </p>
                                        <p>
                                          <strong>Due in: </strong>
                                          <span id="departure-time">${data.results[j].duetime === "Due" ? data.results[j].duetime : data.results[j].duetime+ " Minutes"} </span>
                                        </p>
                                        <p>
                                          <strong>Departure time: </strong>
                                          <span id="departure-time">${data.results[j].departuredatetime}</span>
                                        </p>
                                        <p>
                                          <strong>Destination: </strong>
                                          <span id="departure-time">${data.results[j].destination}</span>
                                        </p>
                                      </li>
                                    </ul>`
                                  );
                            }    
                            } else {
                                $("#x<?php echo $bus['id']?>").append(`<ul class="collection with-header" >
                                    <li class="collection-header grey lighten-4" >
                                        <h5>
                                          <i class="material-icons">directions_bus</i>
                                          <span id="route">Invalid Stop Number: ${stopNumber}</span>
                                        </h5>
                                      </li>
                                      <li class="collection-item" ><p><strong>Error:</strong> This bus stop is invalid!!!</p><br/></li>
                                    </ul>`);
                            }
                            
                        });
                    });
                    </script>
                <!--<span><a href="/edit.php?id=<?php echo $bus['id'] ?>"> Edit </a></span> |-->
                <!--<span><a href="/CRUD/Odelete.php?busId=<?php echo $bus['id']; ?>">Delete</a></span>-->
        <?php endforeach; ?>
        <script type="text/javascript">
        $( document ).ready(function(){
          $(".button-collapse").sideNav();
        })
      </script>
        
</div>
</div>
</body>
</html>