<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('google_places', 'exported_to_lead')) {
            Schema::table('google_places', function (Blueprint $table) {
                $table->boolean('exported_to_lead')->default(false);
            });
        }

        if (!Schema::hasColumn('google_places', 'exported_at')) {
            Schema::table('google_places', function (Blueprint $table) {
                $table->timestamp('exported_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('google_places', function (Blueprint $table) {

            if (Schema::hasColumn('google_places', 'exported_to_lead')) {
                $table->dropColumn('exported_to_lead');
            }

            if (Schema::hasColumn('google_places', 'exported_at')) {
                $table->dropColumn('exported_at');
            }

        });
    }
};