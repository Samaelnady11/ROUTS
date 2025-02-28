<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['school_name', 'school_id'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function children()
    {
        return $this->hasMany(Child::class);
    }
}
