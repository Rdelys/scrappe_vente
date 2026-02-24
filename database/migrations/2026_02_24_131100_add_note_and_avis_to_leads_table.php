<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->decimal('note', 3, 1)
                  ->nullable()
                  ->after('compte_insta');

            $table->integer('avis')
                  ->nullable()
                  ->after('note');

        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['note', 'avis']);
        });
    }
};