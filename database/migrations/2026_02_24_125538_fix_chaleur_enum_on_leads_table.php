<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE leads 
            MODIFY chaleur ENUM(
                'Pas Échangé',
                'Froid',
                'Tiède',
                'Chaud'
            ) NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE leads 
            MODIFY chaleur ENUM(
                'Froid',
                'Tiède',
                'Chaud'
            ) NULL
        ");
    }
};