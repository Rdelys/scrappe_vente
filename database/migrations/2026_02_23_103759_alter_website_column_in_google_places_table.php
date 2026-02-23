<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // Version ultra safe en prod
        DB::statement('ALTER TABLE google_places MODIFY website TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE google_places MODIFY website VARCHAR(255) NULL');
    }
};