<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('mode_paiement', ['en_ligne', 'fin_service']);
            $table->enum('statut', ['en_attente', 'valide', 'echoue'])->default('en_attente');
            $table->string('transaction_id')->nullable();
            $table->dateTime('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
