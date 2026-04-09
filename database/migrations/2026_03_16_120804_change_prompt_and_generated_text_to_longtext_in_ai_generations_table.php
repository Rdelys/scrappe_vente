<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_generations', function (Blueprint $table) {
            $table->longText('prompt')->nullable()->change();
            $table->longText('generated_text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ai_generations', function (Blueprint $table) {
            $table->text('prompt')->nullable()->change();
            $table->text('generated_text')->nullable()->change();
        });
    }
};
