
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
        
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Loading third party fonts -->
        <link href="{{ asset('AdminAssets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />   
        <link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
        <link href="{{ asset('CompassAssets/fonts/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <!-- toastr -->
    <link href="{{ asset('AdminAssets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Loading main css file -->
        <link rel="stylesheet" href="{{ asset('CompassAssets/style.css') }}">
        
        <!--[if lt IE 9]>
        <script src="js/ie-support/html5.js"></script>
        <script src="js/ie-support/respond.js"></script>
        <![endif]-->
        <style type="text/css">
            .bootbox-body{
                color:#1e202b;
            }
        </style>

    </head>


    <body>
        
        <div class="site-content">
            <div class="site-header">
                <div class="container">
                    <a href="{{ url('/') }}" class="branding">
                        <img src="{{ asset('CompassAssets/images/logo.png') }}" alt="" class="logo">
                        <div class="logo-type">
                            <h1 class="site-title"> Shonabondhu  </h1>
                            
                        </div>
                    </a>

                    <!-- Default snippet for navigation -->
                    <div class="main-navigation">
                        <button type="button" class="menu-toggle"><i class="fa fa-bars"></i></button>
                        <ul class="menu">
                            <li class="menu-item current-menu-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="menu-item"><a href="#">Map</a></li>
                            <li class="menu-item"><a href="#">Important Links</a></li>
                             @if (Route::has('login'))
                                
                                    @auth
                                        <li class="menu-item"><a href="{{ url('/home') }}">Dashboard</a></li>
                                    @else
                                        <li class="menu-item"><a href="{{ route('login') }}">Login</a></li>
                                        <li class="menu-item"><a href="{{ route('register') }}">Register</a></li>
                                    @endauth
                               
                            @endif
                        </ul> <!-- .menu -->
                    </div> <!-- .main-navigation -->

                    <div class="mobile-navigation"></div>

                </div>
            </div> <!-- .site-header -->

            <div class="hero" data-bg-image="{{ asset('CompassAssets/images/flood.jpg') }}">
                <div class="container">
                    <form action="#" class="find-location">
                        <input type="text" placeholder="Find your location...">
                        <input type="submit" value="Find">
                    </form>
                      <div class="panel panel-default" style="color:#1e202b">
                        <div class="panel-body">
                               
                             
                      <div class="row">
                        <div class="col-md-6">   
                          <div class="col-md-6">
                           <div class="form-group">
                            <label >Area</label>
                            <select class="form-control" id="areaId">
                              <option value="0">Select</option>
                              @foreach($area as $a)
                              <option value="{{$a->AreaId}}">{{$a->Area->Name}}</option>
                               @endforeach       
                            </select>
                            </div>
                          </div> 

                       <div class="col-md-6">
                        <div class="form-group">
                        <label >Device</label>
                          <select class="form-control" id="deviceId">
                            
                           
                          </select>
                      </div>

                       </div> 
                       <div class="col-md-6">
                       <button type="button" class="btn btn-primary" onclick="getValueFromDb()">Go</button>
                      </div>
                       </div>

                          <div class="col-md-6">
                                
                            <div class="panel panel-default">
                              <div class="panel-body">
                                <div class="row">
                                  <div class="col-md-3">
                                    <strong>Area:</strong><br>
                                    <strong>Device ID:</strong> <br>
                                    <strong>Water Level:</strong><br>
                                    <strong>Updated at:</strong><br>
                                    <strong>Current Time:</strong> 
                                  </div>
                                   <div class="col-md-9">
                                   <span id="areaFeed">NA</span><br>
                                    <span id="deviceFeed">NA</span> <br>
                                    <span id="waterLvlFeed">NA</span><br>
                                    <span id="updatedAtFeed">NA</span><br>
                                    <span id="clock"></span> 
                                  </div>
                                </div>
                              </div>
                              
                            </div>
                                 

                          </div>
                           </div>
                             
                            </div></div>

                                    </div>
                                </div>
           
           

            <footer class="site-footer">
                <div class="container">
                    
                   <div class="row">
                        <div class="col-md-8">
                             <strong>Copyright &copy; 2018 <a href="http://shonabondhu.com">shonabondhu</a>.</strong> All rights reserved.
                        </div>
                        <div class="col-md-3 col-md-offset-1">
                            <div class="social-links">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </footer> <!-- .site-footer -->
        </div>
        
        <script src="{{ asset('CompassAssets/js/jquery-1.11.1.min.js') }}"></script>
        <script src="{{ asset('CompassAssets/js/plugins.js') }}"></script>
        <script src="{{ asset('CompassAssets/js/app.js') }}"></script>
          <!-- Bootstrap 3.3.2 JS -->
        <script src="{{ asset('AdminAssets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>    
         <!-- Moment -->
    <script src="{{ asset('AdminAssets/plugins/moment/moment-with-locales.js') }}" type="text/javascript"></script>
    <!-- Bootbox -->
    <script src="{{ asset('AdminAssets/plugins/bootbox/bootbox.min.js') }}" type="text/javascript"></script>
     <!-- Toast -->
    <script src="{{ asset('AdminAssets/plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>

        <script type="text/javascript">
            var c,uArea,uDevice;


            $("#areaId").change(function(){
                console.log($('#areaId').find(":selected").val());
                let dropdown = $('#deviceId');

                dropdown.empty();

                dropdown.append('<option selected="true" disabled value="0">Select Device</option>');
                dropdown.prop('selectedIndex', 0);

                const url = 'getDeviceList/'+$('#areaId').find(":selected").val();
                uArea = $('#areaId').find(":selected").text();
                if(!($('#areaFeed').text()==="NA") && !($('#areaFeed').text()===uArea)){
                  toastr.options.closeButton = true;
                  toastr.options.timeOut = 5000;
                  toastr.options.extendedTimeOut = 3000;
                  toastr.warning('The Data below was not loaded for the selected area');
                }
                
                $.getJSON(url, function (data) {
                  $.each(data, function (key, entry) {
                    //console.log("For loop"+data);
                    dropdown.append($('<option></option>').attr('value', entry.value).text(entry.name));
                  })
                });
              });

            $("#deviceId").change(function(){            
                uDevice = $('#deviceId').find(":selected").text();
                if(!($('#deviceFeed').text()==="NA") && !($('#deviceFeed').text()===uDevice)){
                  toastr.options.closeButton = true;
                  toastr.options.timeOut = 5000;
                  toastr.options.extendedTimeOut = 3000;
                  toastr.warning('The Data below was not loaded for the selected device');
                }
            
           

            });
        

             function getValueFromDb(){
             var area = $('#areaId').find(":selected").val();
             var device = $('#deviceId').find(":selected").val();           

             if(area==0){
                bootbox.alert("Please select an area first!", function() {
                  console.log("Alert Callback");
                });
                
                return;
             }
             if(device==0){
                bootbox.alert("Please select a device first!", function() {
                  console.log("Alert Callback");
                });
                return;
             }

            $.ajax({
                type: "GET",
                url: "/getLastPointLanding/"+area+"/"+device, 
                success: drawHighChart
             });

                 console.log("back");

            

            }

            function drawHighChart(msg){
                var data = JSON.parse(msg);
                $("#waterLvlFeed").text(data.WaterLevel);
                var a = new Date(parseInt(data.Time)*1000);
                var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                var year = a.getFullYear();
                var month = months[a.getMonth()];
                var date = a.getDate()-1;
                var hour = "0" +a.getUTCHours();
                var min = "0" +a.getMinutes();
                var sec = "0" +a.getSeconds();
                var time = date + ' ' + month + ' ' + year + ' ' + hour.substr(-2) + ':' + min.substr(-2) + ':' + sec.substr(-2) ;
                $('#updatedAtFeed').text(time);
                $('#areaFeed').text(uArea);
                $('#deviceFeed').text(uDevice);
                console.log(time);
            }

         $(document).ready(function() {
          
          $("#panel-info").hide();
          function update() {
            $('#clock').html(moment().format('MMMM Do YYYY, h:mm:ss a'));
          }

          setInterval(update, 1000);
      });
        //=========
        </script>
        
    </body>

</html>