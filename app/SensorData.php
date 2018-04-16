<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    //
	//Hides following values from JSON object
    protected $hidden = ['id','Token','created_at','updated_at'];

    protected $fillable = ['Token', 'AreaId','DeviceId','WaterLevel','Time'];

     public function area()
    {
        return $this->belongsTo('App\Area','AreaId');
    }
}
