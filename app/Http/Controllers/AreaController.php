<?php

namespace App\Http\Controllers;
use App\SensorData;
use App\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //



    public function index()
    {
        return Area::all();
    }

    public function show(Area $area)
    {
        return $area;
    }

    public function store(Request $request)
    {
        $area = Area::create($request->all());        
        $retValue = array('Result'=>'Done');
        return response()->json($retValue, 201);
    }

    public function update(Request $request, Area $area)
    {
        $area->update($request->all());

        return response()->json($area, 200);
    }

    public function delete(Area $area)
    {
        $area->delete();

        return response()->json(null, 204);
    }

  
   

    

}
