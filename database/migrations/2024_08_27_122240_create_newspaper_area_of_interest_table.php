<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area_of_interest_newspaper', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_of_interest_id')->constrained();
            $table->foreignId('newspaper_id')->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_of_interest_newspaper');
    }
};
