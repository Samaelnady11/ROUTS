<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('school_name')->constrained()->onDelete('');
            $table->foreignId('parent_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->decimal('pickup_latitude', 10, 8); // Pickup location latitude
            $table->decimal('pickup_longitude', 11, 8); // Pickup location longitude
            $table->string('pickup_address'); // Human readable address
            $table->string('grade');
            $table->foreignId('bus_id')->nullable()->constrained()->onDelete('set null'); // Which bus picks up this student
            $table->text('health_info')->nullable();
            $table->enum('attendance_status', ['present', 'absent'])->default('absent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
