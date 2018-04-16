@extends('layouts.dashboardLayout')

@section('content')



<div id="chartContainer" style="height: 370px; width:100%;"></div>


</div> 



@endsection


@section('scripts')

   <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


   
 
   <script type="text/javascript">

window.onload = function () {

var dps = []; // dataPoints
var chart = new CanvasJS.Chart("chartContainer", {
  title :{
    text: "Dynamic Data"
  },
  axisY: {
    includeZero: false
  },      
  data: [{
    type: "line",
    dataPoints: dps
  }]
});

var xVal = 0;
var yVal = 100; 
var updateInterval = 1000;
var dataLength = 5; // number of dataPoints visible at any point

var updateChart = function (count) {

  count = count || 1;

 
    $.ajax({
                type: "GET",
                url: "/api/sensor_data/"+1+"/"+1+"/realtime",
                headers:{accept:'application/json',
                         authorization:'Bearer 9rwXJSAARsXWPdRPbBB6D19J70u8xJF35qseFQjlsjQ9E2LyBuYPYy9IL4r5'},
                contentType: 'application/json',
                success: function( msg ) {
                   for(var i=0;i<msg.length;i++){
                       dps.push({
                          x: i,
                          y: msg[i].WaterLevel
                        });
                      
                      
                   }
              }
    });
   
    console.log(dps);
   
   
 

  if (dps.length > dataLength) {
    dps.shift();
  }

  chart.render();
};

updateChart(dataLength);
setInterval(function(){updateChart()}, updateInterval);

}

    /*
        function getValueFromDb(){
             var area = $('#areaId').find(":selected").val();
             var device = $('#deviceId').find(":selected").val();
             var from = $('#datepickerFrom').val();
             var spl = from.split("/");
             var fromDate =  spl[2]+"-"+spl[0]+"-"+spl[1];
             var to = $('#datepickerTo').val();
             spl = to.split("/");
             var toDate =  spl[2]+"-"+spl[0]+"-"+spl[1];

             var returnData = [];


           if(area==0)
              area = 1;
           if(device==0)
              device = 1;
          

            $.ajax({
                type: "GET",
                url: "/api/sensor_data/"+area+"/"+device+"/realtime",
                headers:{accept:'application/json',
                         authorization:'Bearer 9rwXJSAARsXWPdRPbBB6D19J70u8xJF35qseFQjlsjQ9E2LyBuYPYy9IL4r5'},
                contentType: 'application/json',
                success: function( msg ) {
                   for(var i=0;i<msg.length;i++){
                      var sp = msg[i].Time.split(" ");
                      returnData.push([i, msg[i].WaterLevel]);
                      
                   }
              }
             });

            console.log(returnData);
            

            return returnData;

        }
      $(function () {

         $("#datepickerFrom").datepicker();
        $("#datepickerTo").datepicker();


        /*
         * Flot Interactive Chart
         * -----------------------
         */
        // We use an inline data source in the example, usually data would
        // be fetched from a server
        /*
        var data = [], totalPoints = 10;
        function getRandomData() {

          if (data.length > 0)
            data = data.slice(1);

          // Do a random walk
          while (data.length < totalPoints) {

            var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

            if (y < 0) {
              y = 0;
            } else if (y > 100) {
              y = 100;
            }

            data.push(y);
          }

          // Zip the generated y values with the x values
          var res = [];
          for (var i = 0; i < data.length; ++i) {
            res.push([i, data[i]]);
          }

          return res;
        }

        var interactive_plot = $.plot("#interactive", [getValueFromDb()], {
          grid: {
            borderColor: "#f3f3f3",
            borderWidth: 1,
            tickColor: "#f3f3f3"
          },
          series: {
            shadowSize: 0, // Drawing is faster without shadows
            color: "#3c8dbc"
          },
          lines: {
            fill: true, //Converts the line chart to area chart
            color: "#3c8dbc"
          },
          yaxis: {
            min: 0,
            max: 100,
            show: true
          },
          xaxis: {
            show:true
          },

        });

        var updateInterval = 10000; //Fetch data ever x milliseconds
        var realtime = "on"; //If == to on then fetch data every x seconds. else stop fetching
        function update() {

          interactive_plot.setData([getValueFromDb()]);

          // Since the axes don't change, we don't need to call plot.setupGrid()
          interactive_plot.draw();
          if (realtime === "on")
            setTimeout(update, updateInterval);
        }

        //INITIALIZE REALTIME DATA FETCHING
        if (realtime === "on") {
          update();
        }
        //REALTIME TOGGLE
        $("#realtime .btn").click(function () {
          if ($(this).data("toggle") === "on") {
            realtime = "on";
          }
          else {
            realtime = "off";
          }
          update();
        });
        /*
         * END INTERACTIVE CHART
         


       

      });

      /*
       * Custom Label formatter
       * ----------------------
      
      function labelFormatter(label, series) {
        return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
                + label
                + "<br/>"
                + Math.round(series.percent) + "%</div>";
      }
      */
    </script>
@endsection
