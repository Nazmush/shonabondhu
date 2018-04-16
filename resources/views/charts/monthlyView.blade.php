@extends('layouts.dashboardLayout')

@section('content')


<div class="container">
  <div class="row">
    <div class="col-md-4">
    <div class="col-md-6">
      <div class="form-group">
      <label>From</label>
      <p><input type="text" id="datepickerFrom"></p>
      </div>  
   </div> 
   <div class="col-md-6">
      <div class="form-group">
      <label>To</label>
      <p><input type="text" id="datepickerTo"></p>
      </div>  
   </div> 

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

      <div class="col-md-8">
            

             <div class="box box-solid bg-teal-gradient">
                <div class="box-header">
                  <i class="fa fa-th"></i>
                  <h3 class="box-title">Monthly Data</h3>
                  
                  <div class="box-tools pull-right">
                    <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body border-radius-none">
                  <div class="chart" id="line-chart" style="height: 250px;"></div>
                </div><!-- /.box-body -->
                <div class="box-footer no-border">
                 
                </div><!-- /.box-footer -->
              </div><!-- /.box -->


</div>

</div> 

@endsection


@section('scripts')

 
   <script type="text/javascript">
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
              alert("Select Area");
           if(device==0)
              alert("Select Device");

            $.ajax({
                type: "GET",
                url: "/api/sensor_data/"+area+"/"+device+"/"+fromDate+"/"+toDate,
                headers:{accept:'application/json',
                         authorization:'Bearer 9rwXJSAARsXWPdRPbBB6D19J70u8xJF35qseFQjlsjQ9E2LyBuYPYy9IL4r5'},
                contentType: 'application/json',
                success: function( msg ) {
                   for(var i=0;i<msg.length;i++){
                      var sp = msg[i].Time.split(" ");
                      returnData.push({y:sp[0], WaterLevel:msg[i].WaterLevel})
                      
                   }
              }
             });

            console.log(returnData);
            

            return returnData;

        }
     $(function () {
        $("#datepickerFrom").datepicker();
        $("#datepickerTo").datepicker();

          var line = new Morris.Line({
          element: 'line-chart',
          resize: true,
          data: [
            {y: '2011', item1: 2666},
            {y: '2011', item1: 2778},
            {y: '2011', item1: 4912},
            {y: '2011', item1: 3767},
            {y: '2012', item1: 6810},
            {y: '2012', item1: 5670},
            {y: '2012', item1: 4820},
            {y: '2012', item1: 15073},
            {y: '2013', item1: 10687},
            {y: '2013', item1: 8432}
          ],
          xkey: 'y',
          ykeys: ['item1'],
          labels: ['Item 1'],
          lineColors: ['#efefef'],
          lineWidth: 2,
          hideHover: 'auto',
          gridTextColor: "#fff",
          gridStrokeWidth: 0.4,
          pointSize: 4,
          pointStrokeColors: ["#efefef"],
          gridLineColor: "#efefef",
          gridTextFamily: "Open Sans",
          gridTextSize: 10
        });

                    });
    </script>
@endsection
