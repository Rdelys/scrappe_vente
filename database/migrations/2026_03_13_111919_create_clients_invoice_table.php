<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients_invoice', function (Blueprint $table) {

            $table->id();

            // TYPE CLIENT
            $table->enum('type',['professionnel','particulier']);

            // ENTREPRISE
            $table->string('company_name')->nullable();
            $table->string('siret')->nullable();
            $table->string('tva')->nullable();

            // PARTICULIER
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            // CONTACT PRINCIPAL
            $table->string('email');
            $table->string('phone')->nullable();

            // CONTACT SECONDAIRE
            $table->string('contact_firstname')->nullable();
            $table->string('contact_lastname')->nullable();
            $table->string('contact_function')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // ADRESSE
            $table->string('address');
            $table->string('address_complement')->nullable();
            $table->string('postal_code');
            $table->string('city');
            $table->string('country')->default('France');

            // BANQUE
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();

            // OPTIONS
            $table->boolean('include_address')->default(true);

            // NOTES
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients_invoice');
    }
};