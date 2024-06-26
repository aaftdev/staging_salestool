<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->string('name');
            $table->string('email');
            $table->string('program_name');
            $table->string('batch_name');
            $table->string('contact');
            $table->string('state');
            $table->string('address');
            $table->string('other')->nullable();
            $table->string('fees');
            $table->string('paid');
            $table->string('txnid');
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
        Schema::dropIfExists('payments');
    }
}
