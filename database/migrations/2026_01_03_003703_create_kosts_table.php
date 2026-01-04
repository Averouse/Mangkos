<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('address');
            $table->decimal('price', 10, 2);
            $table->enum('type', ['putra', 'putri', 'campur']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('total_rooms')->default(0);
            $table->integer('available_rooms')->default(0);
            $table->json('facilities')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};
