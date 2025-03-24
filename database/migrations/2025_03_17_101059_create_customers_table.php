<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('licenseNumber');
            $table->date('licenseValidUntil');
            $table->string('nationalID');
            $table->date('identityCardValidUntil');
            $table->date('birthDate');
            $table->string('cardNumber');
            $table->string('expirationDate');
            $table->string('cvv');
            $table->string('cardHolder');
            $table->string('language');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
