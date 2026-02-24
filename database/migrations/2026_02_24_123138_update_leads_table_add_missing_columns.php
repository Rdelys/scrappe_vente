<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            // Identité
            if (!Schema::hasColumn('leads', 'nom')) {
                $table->string('nom')->nullable()->after('prenom_nom');
            }

            // Entreprise
            if (!Schema::hasColumn('leads', 'categorie')) {
                $table->string('categorie')->nullable()->after('entreprise');
            }

            if (!Schema::hasColumn('leads', 'adresse_postale')) {
                $table->string('adresse_postale')->nullable()->after('categorie');
            }

            // Message formulaire
            if (!Schema::hasColumn('leads', 'message_form')) {
                $table->string('message_form')->nullable()->after('messenger');
            }

            // Email gérant
            if (!Schema::hasColumn('leads', 'email_gerant')) {
                $table->string('email_gerant')->nullable()->after('email');
            }

            // URLs supplémentaires
            if (!Schema::hasColumn('leads', 'url_facebook')) {
                $table->string('url_facebook')->nullable()->after('portable');
            }

            if (!Schema::hasColumn('leads', 'url_instagramm')) {
                $table->string('url_instagramm')->nullable()->after('url_facebook');
            }

            // Vérifie si follow_insta existe et force default false si nécessaire
            if (Schema::hasColumn('leads', 'follow_insta')) {
                $table->boolean('follow_insta')->default(false)->change();
            }

        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $columns = [
                'nom',
                'categorie',
                'adresse_postale',
                'message_form',
                'email_gerant',
                'url_facebook',
                'url_instagramm',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};