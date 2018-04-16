@extends('layouts.dashboardLayout')

@section('content')


<div class="container">
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
          <option value="{{$a->id}}">{{$a->Name}}</option>
           @endforeach       
        </select>
        </div>
      </div> 

   <div class="col-md-6">
    <div class="form-group">
    <label >Device</label>
      <select class="form-control" id="deviceId">
        <option value="0">Select</option>
        @foreach($device as $d)
         <option>{{$d->DeviceId}}</option>
        @endforeach
       
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
            <strong>Area:</strong><br>
            <strong>Device ID:</strong> <br>
            <strong>Water Level:</strong> <br>
            <strong>Current Time:</strong> 
          </div>
          
        </div>
             

      </div>
       </div>
         
        </div></div>
   
      
            
        <div class="panel panel-default">
          <div class="panel-body">
            <div id="Chartcontainer"></div>
          </div>
          <div class="panel-footer"><p align="right">This chart updates in every 10 seconds</p></div>
        </div>
             

      
      

  </div> 
</div>

@endsection


@section('scripts')

     <script src="{{ asset('AdminAssets/plugins/Highcharts/highcharts.js') }}" type="text/javascript"></script>

      <script src="{{ asset('AdminAssets/plugins/Highcharts/modules/exporting.js') }}" type="text/javascript"></script>

      
 
   <script type="text/javascript">

        $(document).ready(function() {

          //==
        
         Highcharts.setOptions({
            global: {
                useUTC: true
            }
        });

      var c = Highcharts.chart('Chartcontainer', {
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
                  return '<b>' + this.series.name + '</b><br/>' +
                      Highcharts.dateFormat('%d-%m-%Y %H:%M:%S', this.x) + '<br/>' +
                      Highcharts.numberFormat(this.y, 2);
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
                     
                  @foreach ($datas as $d)
                      //var date = new Date("{{$d->Time}}"); 
                      data.push({
                              x: {{$d->Time*1000}},
                              y: {{ $d->WaterLevel }}
                          });
                     
                  @endforeach
                 
                  return data;
              }())
          }]
      });
      $(".highcharts-credits").text("");
    
    


 
      function callAjax(){
        $.getJSON('getChartData/'+c.series[0].data[9].x/1000+'/'+c.series[0].data[9].y, function(data) {
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
    
    </script>
@endsection
