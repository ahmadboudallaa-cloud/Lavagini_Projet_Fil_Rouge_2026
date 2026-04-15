<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones_geographiques', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('ville');
            $table->string('code_postal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones_geographiques');
    }
};
