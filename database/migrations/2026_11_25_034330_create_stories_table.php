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
        Schema::create('stories', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->enum('type', ['cerpen', 'novel'])->default('cerpen');
    $table->string('genre')->nullable();
    $table->text('synopsis')->nullable();
    $table->longText('content');
    $table->string('cover_path')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
