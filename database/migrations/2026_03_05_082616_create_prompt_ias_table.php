<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('prompt_ias', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('client_id')->nullable();

        $table->string('nom_groupe');
        $table->text('prompt');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_ias');
    }
};
