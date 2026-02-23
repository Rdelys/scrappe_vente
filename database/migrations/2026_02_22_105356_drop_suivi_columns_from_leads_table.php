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
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'suivi_mail')) {
                $table->dropColumn('suivi_mail');
            }

            if (Schema::hasColumn('leads', 'suivi_whatsapp')) {
                $table->dropColumn('suivi_whatsapp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('suivi_mail')->nullable();
            $table->boolean('suivi_whatsapp')->nullable();
        });
    }
};