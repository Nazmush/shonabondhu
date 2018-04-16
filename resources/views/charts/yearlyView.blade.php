@extends('layouts.dashboardLayout')

@section('content')

<div class="container" style="width:97%">
  <div class="panel panel-default">
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
   <button type="button" class="btn btn-primary" onclick="getValueFromDb()">GO</button>
  </div>
   </div>

      <div class="col-md-6">
            
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <strong>Area:</strong><br>
                <strong>Device ID:</strong> <br>
                <strong>Total Data Points:</strong><br>
                <strong>Current Time:</strong> 
              </div>
               <div class="col-md-8">
               <span id="areaFeed">NA</span><br>
                <span id="deviceFeed">NA</span> <br>
                <span id="totalPointsFeed">NA</span><br>
                <span id="clock"></span> 
              </div>
            </div>
          </div>
          
        </div>
             

      </div>
       </div>
         
        </div></div>
   
      
            
        <div class="panel panel-default" id="panel-info">
          <div class="panel-body">
            <div id="Chartcontainer"></div>
          </div>
          <div class="panel-footer"><p align="right" >Click and drag in the plot area to zoom in</div>
        </div>
             

      
      

  </div> 
</div>
@endsection



 @section('scripts')

     <script src="{{ asset('AdminAssets/plugins/Highcharts/highcharts.js') }}" type="text/javascript"></script>

      <script src="{{ asset('AdminAssets/plugins/Highcharts/modules/exporting.js') }}" type="text/javascript"></script>

      
 
   <script type="text/javascript">
 
        var c,uArea,uDevice,totalPoints;


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
              toastr.warning('The chart below was not loaded for the selected area');
            }
            
            $.getJSON(url, function (data) {
              $.each(data, function (key, entry) {
                //console.log("For loop"+data);
                dropdown.append($('<option></option>').attr('value', entry.value).text(entry.name));
              })
            });
          });
        //=========

         $("#deviceId").change(function(){            
            uDevice = $('#deviceId').find(":selected").text();
            if(!($('#deviceFeed').text()==="NA") && !($('#deviceFeed').text()===uDevice)){
              toastr.options.closeButton = true;
              toastr.options.timeOut = 5000;
              toastr.options.extendedTimeOut = 3000;
              toastr.warning('The chart below was not loaded for the selected device');
            }
            
           

          });
        

        function drawHighChart(msg){
          totalPoints=0;
            console.log(msg);
            $("#panel-info").show();
            Highcharts.chart('Chartcontainer', {
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'All Time Water Level'
            },
            
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Water Level (cm)'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

                series: [{
                    name: 'Water Level',
                    type: 'area',
                    zones: [{
                        value: 20,
                        color: '#90ed7d'
                    },  {
                        value: 40,
                        color: '#f7a35c'
                    }, {
                        color: '#ff0000'
                    }],
                    
                    data: (function () {
                        // generate an array of random data
                        var data = [];
                        var array = JSON.parse(msg);
                        console.log(array);
                        for(var i=0;i<array.length;i++){
                          console.log(parseInt(array[i].Time));
                          data.push([
                                    parseInt(array[i].Time)*1000,
                                    parseFloat(array[i].Level)
                          ]);
                          totalPoints++;
                         
                        }  
                        return data;
                    }())
                }]
            });
            $(".highcharts-credits").text("");         
            $("#areaFeed").text(uArea);
            $("#deviceFeed").text(uDevice);
            $("#totalPointsFeed").text(totalPoints);

            

            
                      
            
        }

       
        //======
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
                url: "/getDataForYearly/"+area+"/"+device, 
                success: drawHighChart
             });

             console.log("back");

            

        }


      $(document).ready(function() {
          
          $("#panel-info").hide();
          function update() {
            $('#clock').html(moment().format('MMMM Do YYYY, h:mm:ss a'));
          }

          setInterval(update, 1000);
      });
  
    </script>

      
@endsection


