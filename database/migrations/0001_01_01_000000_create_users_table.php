<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email', 191)->unique();
            $table->string('telephone', 20);
            $table->text('adresse');
            $table->string('mot_passe');
            $table->enum('role', ['menage', 'entreprise', 'collecteur', 'admin', 'partenaire'])->default('menage');
            $table->enum('statut_compte', [
                'inscrit',
                'commande_kit',
                'kit_livre',
                'essai_15j',
                'abonne_actif',
                'en_attente_paiement',
                'inactif'
            ])->default('inscrit');
            $table->integer('score_total')->default(0);
            $table->timestamp('date_inscription')->useCurrent();
            $table->timestamp('date_derniere_connexion')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
