<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_smtps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('encryption')->nullable();
            $table->string('username');
            $table->text('password');
            $table->string('from_name')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('last_test_success')->nullable();
            $table->timestamp('last_tested_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_smtps');
    }
};
