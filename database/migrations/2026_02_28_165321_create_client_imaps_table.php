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
    Schema::create('client_imaps', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id');

        $table->string('host');
        $table->integer('port');
        $table->string('encryption')->nullable();
        $table->string('username');
        $table->text('password');

        $table->string('folder')->default('INBOX');

        $table->boolean('last_test_success')->nullable();
        $table->timestamp('last_tested_at')->nullable();
        $table->timestamp('last_sync_at')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_imaps');
    }
};
