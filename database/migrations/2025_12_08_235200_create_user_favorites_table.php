<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('favoritable_type'); // Model class name
            $table->unsignedBigInteger('favoritable_id')->nullable(); // For specific records
            $table->string('route_name')->nullable(); // For route favorites
            $table->string('label'); // Display name
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'favoritable_type']);
            $table->index(['user_id', 'route_name']);
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id', 'route_name'], 'user_favorite_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_favorites');
    }
};
