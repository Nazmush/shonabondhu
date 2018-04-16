<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use App\SensorData;
use App\Area;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>['getLastPointLanding','getDeviceList','landing']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function landing(){
        $area = SensorData::distinct()->get(['AreaId']);           
        
        
        
        return view('Home.landing',array('area' => $area));
    }
    
    public function index()
    {
        $area = SensorData::distinct()->get(['AreaId']);           
        
        
        
        return view('home',array('area' => $area));
    }


    

    public function monthly()
    {

        $area = DB::table('areas')
                    ->join('sensor_datas', 'areas.id', '=', 'sensor_datas.AreaId')
                    ->select('Name,AreaId')->distinct()->get();        
        $deviceId = SensorData::select('DeviceId')->distinct()->get();

      
        return view('charts.monthlyView',array('area' => $area,
               'device' => $deviceId));
     
    }

    public function yearly()
    {
        $area = SensorData::distinct()->get(['AreaId']); 
        return view('charts.yearlyView',array('area' => $area));
    }

    public function getDataForYearly($areaId,$deviceId)
    {
        $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->orderBy('created_at')->get(); 
        $arr = array();
        foreach($sensordatas as $sd){
            array_push($arr, ["Time"=>$sd->Time,"Level"=>$sd->WaterLevel]);
        }
        return json_encode($arr);
    }



    public function realtime()
    {


        $user = User::find(1);        
       

      
        return $user->api_token;
        
    }


    public function getLastPoint($areaId,$deviceId,$timestamp,$level)
    {
        $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->orderBy('created_at','desc')->take(1)->get();

        foreach($sensordatas as $sd){
            $ts = $sd->Time;
            $wl = $sd->WaterLevel;
            
        }         
       
        if($ts==$timestamp && $level ==$wl)
            $arr = array('data'=> array("Old","Old"));
            
        else
            $arr = array('data'=> array($ts,$wl));
        return '['.json_encode($arr).']';
        
             

    }


    public function getChartData($areaId,$deviceId)
    {
        $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->select('Time','WaterLevel')->orderBy('created_at','desc')->take(10)->get();
        
        $sensordatas = $sensordatas->reverse(); 

        $arr = array();

        foreach($sensordatas as $sd){
           array_push($arr, array('Time'=>$sd->Time,'WaterLevel'=>$sd->WaterLevel));
            
        } 

        return json_encode($arr);


    }

    public function getDeviceList($areaId)
    {
        $deviceId = SensorData::where('AreaId', $areaId)->select('DeviceId')->distinct()->get();
        $arr = array();
        foreach($deviceId as $sd){
           array_push($arr, array('value'=>$sd->DeviceId,'name'=>$sd->DeviceId));
            
        } 
        return json_encode($arr);
        
             

    }

    public function getLastPointLanding($areaId,$deviceId){
        $sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->orderBy('created_at','desc')->take(1)->get();

        foreach($sensordatas as $sd){
            $ts = $sd->Time;
            $wl = $sd->WaterLevel;
            
        }         
       
        
        $arr = array('Time'=>$ts,'WaterLevel'=>$wl);
        return json_encode($arr);
    }


    public function getMap(){
        $area = SensorData::distinct()->get(['AreaId']);  
        
        $arr = array();

        foreach($area as $a){
            $sd = SensorData::where('AreaId',$a->AreaId)->orderBy('created_at','desc')->take(1)->get();
            array_push($arr, array('Name'=>$a->Area->Name,'WaterLevel'=>$sd[0]->WaterLevel,'Updated_at'=>$sd[0]->Time));
        }         
        //$sensordatas = SensorData::where('AreaId',$areaId)->where('DeviceId',$deviceId)->orderBy('created_at','desc')->take(1)->get();
        
        /*$sensordatas = DB::table('areas')
            ->join('sensor_datas', 'areas.id', '=', 'sensor_datas.AreaId')
            ->select(DB::raw('areas.Name, sensor_datas.WaterLevel, max(sensor_datas.created_at)'))
            ->groupBy('sensor_datas.AreaId')
            ->get();
                     */ 
            
        //print_r($arr);
        
        return view('Home.map',array('area' => $area,'data'=>$arr));
    }

    public function data_map(){
        $area = SensorData::distinct()->get(['AreaId']);  
        
        $arr = array();

        foreach($area as $a){
            $sd = SensorData::where('AreaId',$a->AreaId)->orderBy('created_at','desc')->take(1)->get();
            array_push($arr, array('Name'=>$a->Area->Name,'WaterLevel'=>$sd[0]->WaterLevel,'Updated_at'=>$sd[0]->Time));
        }         
       
        
        return view('charts.data_map',array('data'=>$arr));
    }




    
}
