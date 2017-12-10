<?php
  require_once __DIR__ . '/vendor/autoload.php';
  require_once __DIR__ . '/config/helpers.php';
  require_once __DIR__ . '/config/database.php';
  
  (new Dotenv\Dotenv(__DIR__ . '/config'))->load();
  requireAuth();
  $page_title = "Journeys";
  $page_name = "List Of Buses";
  include __DIR__ . "/inc/header.php";
  include __DIR__ . "/inc/footer.php";
?>

<div class="container">
  <div id="numfact" ></div>
  <div id="bus<?php echo $bus['id']?>" ></div>
  
  <script type="text/javascript">
    var proxy = 'https://cors-anywhere.herokuapp.com/';
    var myUrl = 'http://numbersapi.com/random/math?json';
    $(function(){
      url = proxy + myUrl;
      $.getJSON(url).done(function(data){
          $("#numfact").append(`
                <ul class="collection with-header">
                  <li class="collection-header grey lighten-4">
                    <h4>Your lucky travel Number is: ${data.number}</h4>
                  </li>
                  <li class="collection-item">
                    <ul class="collection with-header" >
                      <li class="collection-header grey lighten-4" >
                        <h5>
                          <i class="material-icons">all_inclusive</i>
                          <span id="route">Here's a luck fact about your lucky travel number!</span>
                        </h5>
                      </li>
                      <li class="collection-item" >
                        <p>
                          <strong>Lucky Fact: </strong>
                          <span id="departure-time">${data.text}</span>
                        </p>
                      </li>
                    </ul>
                  </li>
                </ul>
              `);
            }
        )
    });
  </script>

  <?php foreach (getAllBuses() as $bus): ?>
    <ul class="collection with-header">
      <li class="collection-header grey lighten-4">
        <h4>
          <?php echo $bus['title']; ?>
          <div class="right">
            <a href="/edit.php?id=<?php echo $bus['id'] ?>" class="btn-floating">
              <i class="material-icons">create</i>
            </a>
            <a href="/operations/crud/odelete.php?busId=<?php echo $bus['id']; ?>" class="btn-floating">
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
        <br/>
        <div id="bus<?php echo $bus['id']?>" />
      </li>
    </ul>

    <script type="text/javascript">
      stopNumber = <?php echo json_encode( $bus['stopno'] ); ?>;
      $(function() {
        $("#bus<?php echo $bus['id']?>").append();
        $.getJSON('https://data.dublinked.ie/cgi-bin/rtpi/realtimebusinformation?stopid='+<?php echo json_encode( $bus['stopno'] ); ?>+'&format=json').done(function(data){
            if (data.errormessage === ""){
              for(let j in data.results){
                $("#bus<?php echo $bus['id']?>").append(`
                  <ul class="collection with-header" >
                    <li class="collection-header grey lighten-4" >
                        <h5>
                          <i class="material-icons">directions_bus</i>
                          <span id="route">${data.results[j].route}</span>
                        </h5>
                    </li>
                    <li class="collection-item" >
                      <p>
                        <strong>Bus Number: </strong>
                        <span id="bus">${data.results[j].route}</span>
                      </p>
                      <p>
                        <strong>Due in: </strong>
                        <span id="due">${data.results[j].duetime === "Due" ? data.results[j].duetime : data.results[j].duetime+ " Minutes"} </span>
                      </p>
                      <p>
                        <strong>Departure time: </strong>
                        <span id="departure-time">${data.results[j].departuredatetime}</span>
                      </p>
                      <p>
                        <strong>Destination: </strong>
                        <span id="destination">${data.results[j].destination}</span>
                      </p>
                    </li>
                  </ul>
                `);
              }    
            } else {
              $("#bus<?php echo $bus['id']?>").append(`
                <ul class="collection with-header" >
                  <li class="collection-header grey lighten-4" >
                    <h5>
                      <i class="material-icons">error</i>
                      <span id="bus">Invalid Stop Number: ${stopNumber}</span>
                    </h5>
                  </li>
                  <li class="collection-item" >
                    <p>
                      <strong>Error:</strong> 
                      This bus stop is invalid!!!</p>
                    <br/>
                  </li>
                </ul>
              `);
            }
        });
      });
    </script>
  <?php endforeach; ?>
</div>