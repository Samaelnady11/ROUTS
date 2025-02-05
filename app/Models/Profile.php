<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    Schema::create('profiles', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone_number')->nullable();
        $table->string('gender');
        $table->date('date_of_birth');
        $table->string('profile_picture')->nullable();
        $table->timestamps();
    });
     //
}
