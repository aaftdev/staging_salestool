<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('VchNo')->nullable();
            $table->dateTime('VchDate')->nullable();
            $table->string('LedgerName');
            $table->string('State');
            $table->string('EnrollmentNo');
            $table->dateTime('BatchStartDate')->nullable();
            $table->string('Batch');
            $table->string('FeeHead')->nullable();
            $table->string('Amount');
            $table->string('PaymentMode');
            $table->string('ReferenceNo');
            $table->dateTime('ReferenceDate')->nullable();
            $table->string('BankName')->nullable();
            $table->string('Branch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
