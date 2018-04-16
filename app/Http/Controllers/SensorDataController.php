<?php

namespace App\Http\Controllers;
use App\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    //



    public function index()
    {
        return SensorData::all();
    }

    public function show(SensorData $sensordata)
    {
        return $sensordata;
    }

    public function store(Request $request)
    {
        $sensordata = SensorData::create($request->all());
        $sensordata->Time=strtotime($request->Time);
        $sensordata->save();
        $retValue = array('Result'=>'Done');
        return response()->json($retValue, 201);
    }

    public function update(Request $request, SensorData $sensordata)
    {
        $sensordata->update($request->all());

        return response()->json($sensordata, 200);
    }

    public function delete(SensorData $sensordata)
    {
        $sensordata->delete();

        return response()->json(null, 204);
    }

    //Custom

    public function getSensorDataByDate($areaId,$deviceId,$from,$to)
    {
          
        $fromDate = $from.' 00:00:00'; 
        $toDate = $to.' 00:00:00';  
        $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->whereBetween('Time', [$fromDate, $toDate])->get();          

        if(sizeof($sensordatas)==0){
              $retValue =  array('Result'=>'No data available in the provided range');
              return response()->json($retValue,404);
        }
        return response()->json($sensordatas, 200);

               
    }


    

    public function getSensorDataRealtime($areaId,$deviceId)
    {
          
       
       $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->orderBy('created_at','desc')->take(5)->get();          

        
       return response()->json($sensordatas, 200);

               
    }

    

}
