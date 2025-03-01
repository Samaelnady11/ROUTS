<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeofenceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'notification_radius'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
