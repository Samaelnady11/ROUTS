<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('role_based_id')->unique(); // Unique ID for roles (driver, supervisor, etc.)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['Parent', 'Driver', 'Supervisor', 'School Admin']);
            $table->string('phone_number');
            $table->string('avatar')->nullable();
            $table->string('national_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('facebook_token')->nullable();
            $table->string('google_id')->nullable();
            $table->string('google_token')->nullable();
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
