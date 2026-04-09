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
    Schema::create('client_scheduled_mails', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id');
        $table->string('to');
        $table->string('subject');
        $table->longText('body');
        $table->timestamp('scheduled_at');
        $table->enum('status', ['pending','sent','failed'])->default('pending');
        $table->text('error_message')->nullable();
        $table->timestamp('sent_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_scheduled_mails');
    }
};
