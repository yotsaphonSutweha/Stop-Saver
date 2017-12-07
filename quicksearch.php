<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

(new Dotenv\Dotenv(__DIR__ . '/config'))->load();
$page_title = "Quick Search";
$page_name = "Search";
include __DIR__ . "/inc/header.php";
include __DIR__ . "/inc/footer.php";

?>

<div class="container">
      
      
          
      
      <label>Stop Number</label>
      <input type="text" name="stop_number" id="quick" placeholder="Stop Number"/>
        <button class="btn waves-effect waves-light" onclick="getBus()" type="submit" on name="action">
        Search
        <i class="material-icons right">send</i>
      </button>
 
        
      

      
    </script>
    
    

        <div id="x"></div>
      

                <div id="details" ><script type="text/javascript">
                function getBus(){
                    let x= $("#quick").val();
                    
                    
                    $(function() {
                      
                    
                        
                        $.getJSON('https://data.dublinked.ie/cgi-bin/rtpi/realtimebusinformation?stopid='+x+'&format=json').done(function(data){
                            console.log(data);
                            if (data.errormessage === ""){
                            for(let j in data.results){
                                $("#x").append(
                                    `<br/>
                                <ul class="collection with-header" >
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
                                          <span id="route">Invalid Stop Number: ${cures}</span>
                                        </h5>
                                      </li>
                                      <li class="collection-item" ><p><strong>Error:</strong> This bus stop is invalid!!!</p><br/></li>
                                    </ul>`);
                            }
                            
                        });
                    });
                }
</script>