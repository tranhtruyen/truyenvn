<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('origin_name')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->nullable();
            $table->text('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->bigInteger('total_views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
