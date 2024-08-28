<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('time_spent')->default(0);
            $table->string('url');
            $table->morphs('trackable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_trackers');
    }
};
