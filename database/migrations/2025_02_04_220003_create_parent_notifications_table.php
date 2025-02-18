<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parent_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('fcm_token')->nullable(); // Store FCM token for notifications
            $table->boolean('notify_on_approach')->default(true);
            $table->boolean('notify_on_pickup')->default(true);
            $table->boolean('notify_on_dropoff')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_notifications');
    }
}; 