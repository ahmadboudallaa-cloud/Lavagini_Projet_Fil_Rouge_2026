<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('zones_geographiques')->onDelete('set null');
            $table->integer('nombre_vehicules');
            $table->text('adresse_service');
            $table->enum('statut', ['demande', 'assignee', 'en_cours', 'terminee', 'payee'])->default('demande');
            $table->enum('mode_paiement', ['en_ligne', 'fin_service']);
            $table->decimal('montant', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
