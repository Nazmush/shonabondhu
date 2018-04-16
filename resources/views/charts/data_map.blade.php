@extends('layouts.dashboardLayout')

@section('content')

<div class="container" style="width:97%">  
   
      
            
        <div class="panel panel-default" id="panel-info">
          <div class="panel-heading"><h4>Data map</h4></div>
          <div class="panel-body">
            <div id="visualization" style="margin: 1em"> </div>
          </div>
          <div class="panel-footer"><p align="right" ></div>
        </div>
             

      
      

  </div> 
</div>
@endsection


@section('scripts')
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
          function getTime(t){
            var a = new Date(parseInt(t)*1000);
                var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                var year = a.getFullYear();
                var month = months[a.getMonth()];
                var date = a.getDate()-1;
                var hour = "0" +a.getUTCHours();
                var min = "0" +a.getMinutes();
                var sec = "0" +a.getSeconds();
                var time = date + ' ' + month + ' ' + year + ' ' + hour.substr(-2) + ':' + min.substr(-2) + ':' + sec.substr(-2) ;
                console.log(time.toString());
                return time.toString();
          }
            google.charts.load('current', {
          
              'packages':['geochart'],
              // Note: you will need to get a mapsApiKey for your project.
              // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
              'mapsApiKey': 'AIzaSyCPLd2FowiiGLkbCs9g444mxpT46iwNI9I'
            });
            google.charts.setOnLoadCallback(drawVisualization);

            function drawVisualization() {
              var data = google.visualization.arrayToDataTable([
                ['City', 'Current Water Level'],
                @foreach ($data as $d)
                    [ "{{ $d['Name'] }}", {{ $d['WaterLevel'] }}], 
                @endforeach
                
                
              ]);
              console.log(data);
              
              var opts = {
                region: 'BD',
                displayMode:'markers',
                resolution:'provinces',
                sizeAxis: { minValue: 0, maxValue: 100 },
                colorAxis: {colors: ['#00853f', 'black', '#e31b23']},
                backgroundColor: '#81d4fa',                
                keepAspectRatio:true
                
                


              };
              var geochart = new google.visualization.GeoChart(
                  document.getElementById('visualization'));
                  geochart.draw(data, opts);
            };
        </script>



 
@endsection  

@section('styles')
<style>
           #visualization path {
  stroke-width:1; /* control the countries borders width */
  stroke:#6699cc; /* choose a color for the border */
  }
</style>
@endsection  