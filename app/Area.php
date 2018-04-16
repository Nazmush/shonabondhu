<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['Name'];

    public function sensordatas()
    {
        return $this->hasMany('App\SensorData','AreaId');
    }
}
