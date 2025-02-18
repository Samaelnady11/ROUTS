<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusLocation extends Model
{
    protected $fillable = [
        'bus_id',
        'latitude',
        'longitude',
        'speed',
        'location_timestamp'
    ];

    protected $casts = [
        'location_timestamp' => 'datetime',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
} 