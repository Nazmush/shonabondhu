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
                <strong>Water Level:</strong><br>
                <strong>Current Time:</strong> 
              </div>
               <div class="col-md-8">
               <span id="areaFeed">NA</span><br>
                <span id="deviceFeed">NA</span> <br>
                <span id="waterLvlFeed">NA</span><br>
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
          <div class="panel-footer"><p align="right" >This chart updates in every <span id="UpdateTime"></span> seconds</p></div>
        </div>
             

      
      

  </div> 
</div>

@endsection


@section('scripts')

     <script src="{{ asset('AdminAssets/plugins/Highcharts/highcharts.js') }}" type="text/javascript"></script>

      <script src="{{ asset('AdminAssets/plugins/Highcharts/modules/exporting.js') }}" type="text/javascript"></script>

      
 
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
            console.log(msg);
            $("#panel-info").show();

            Highcharts.setOptions({
                  global: {
                      useUTC: true
                  }
            });

            c = Highcharts.chart('Chartcontainer', {
                chart: {
                    type: 'spline',
                    animation: Highcharts.svg, // don't animate in old IE
                    marginRight: 10,
                    
                },
                title: {
                    text: 'Real Time Water Level'
                },
                xAxis: {
                    title: {
                        text: 'Time'
                    },
                    type: 'datetime',
                    tickPixelInterval: 50
                },
                yAxis: {
                    title: {
                        text: 'Water Level in cm'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
                },
                tooltip: {
                    formatter: function () {
                        return '<b>Time</b><br/>' +
                            Highcharts.dateFormat('%d-%m-%Y %H:%M:%S', this.x) + '<br/>' +
                            '<b>' + this.series.name +' : </b>'+Highcharts.numberFormat(this.y, 2);
                    }
                },
                legend: {
                    enabled: false
                },

                series: [{
                    name: 'Water Level',
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
                          data.push({
                                    x: parseInt(array[i].Time)*1000,
                                    y: parseFloat(array[i].WaterLevel)
                          });
                         
                        }  
                        return data;
                    }())
                }]
            });
            $(".highcharts-credits").text("");
            var lastIndex = c.series[0].data.length-1;
            $("#waterLvlFeed").text(c.series[0].data[lastIndex].y);            
            $("#areaFeed").text(uArea);
            $("#deviceFeed").text(uDevice);
            

            callAjax(c);
                      
            
        }

       

         function callAjax(c){
                    var area = $('#areaId').find(":selected").val();
                    var device = $('#deviceId').find(":selected").val();           

                    if(area==0){                        
                        return;
                     }
                    if(device==0){                        
                        return;
                    }

                    var lastIndex = c.series[0].data.length-1;
                    //console.log(lastIndex);
                    $.getJSON('getLastPoint/'+$('#areaId').find(":selected").val()+'/'+$('#deviceId').find(":selected").val()+'/'+c.series[0].data[lastIndex].x/1000+'/'+c.series[0].data[lastIndex].y, function(data) {
                       // console.log(c.series[0].data);
                        
                        var seriesOneNewPoint = data[0].data[0];
                        //console.log("Old "+c.series[0].data[0].x+" "+c.series[0].data[0].y);
                        //console.log("New "+seriesOneNewPoint+" "+data[0].data[1]);
                        if(seriesOneNewPoint == "Old" && data[0].data[1] == "Old"){
                          console.log("Nice");              
                        }else{
                          //console.log(c.series[0].data);
                           if(c.series[0].data.length==10)
                                c.series[0].data[0].remove(false);              
                           c.series[0].addPoint([seriesOneNewPoint*1000,parseFloat(data[0].data[1])], false, false);

                           
                           c.redraw();
                           var lastIndex = c.series[0].data.length-1;
                           $("#waterLvlFeed").text(c.series[0].data[lastIndex].y);
                           //console.log(c.series[0].data);
                           //console.log("Added new point");

                        }
                      var refrehInterval = 10000;
                      setTimeout(function() {
                            callAjax(c);
                        }, refrehInterval);
                      $('#UpdateTime').text(10000/1000);
                    });
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
                url: "/getChartData/"+area+"/"+device, 
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
   /*       
          //=========
       
    
    


 
      function callAjax(){
        $.getJSON('getLastPoint/'+c.series[0].data[9].x/1000+'/'+c.series[0].data[9].y, function(data) {
           // console.log(c.series[0].data);
            
            var seriesOneNewPoint = data[0].data[0];
            //console.log("Old "+c.series[0].data[0].x+" "+c.series[0].data[0].y);
            //console.log("New "+seriesOneNewPoint+" "+data[0].data[1]);
            if(seriesOneNewPoint == "Old" && data[0].data[1] == "Old"){
              //console.log("Nice");              
            }else{
              //console.log(c.series[0].data);
               c.series[0].data[0].remove(false);              
               c.series[0].addPoint([seriesOneNewPoint*1000,parseFloat(data[0].data[1])], false, false);

               
               c.redraw();
               //console.log(c.series[0].data);
               //console.log("Added new point");

            }
             
          setTimeout(callAjax, 10000);
        });
      }

      callAjax();


});
    */
    </script>
@endsection
