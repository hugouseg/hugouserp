<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_report_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('report_type')->index();
            $table->json('filters')->nullable();
            $table->json('columns')->nullable();
            $table->json('ordering')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'report_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_report_views');
    }
};
