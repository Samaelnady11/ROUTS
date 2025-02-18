<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key relation
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->string('profile_picture')->nullable(); // To allow NULL for profile picture
            $table->timestamps(); // This automatically adds created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
}
};