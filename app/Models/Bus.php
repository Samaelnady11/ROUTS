<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';

    protected $fillable = [
        'bus_number',
        'driver_id',
        'supervisor_id',
        'route_id',
        'capacity',
        'current_latitude',
        'current_longitude',
    ];

    // Relationship with Driver (Client)
    public function driver()
    {
        return $this->belongsTo(Client::class, 'driver_id', 'role_based_id');
    }

    // Relationship with Supervisor (Client)
    public function supervisor()
    {
        return $this->belongsTo(Client::class, 'supervisor_id', 'role_based_id');
    }

    // Relationship with Route
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    // Update the bus's current location
    public function updateLocation($latitude, $longitude)
    {
        $this->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
        ]);
    }

    public function currentLocation()
    {
        return $this->hasOne(BusLocation::class)->latest();
    }

    public function locations()
    {
        return $this->hasMany(BusLocation::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function geofenceSetting()
    {
        return $this->hasOne(GeofenceSetting::class);
    }
}
