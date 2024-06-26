<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('email');
            $table->longText('counsellor')->nullable();
            $table->longText('contact')->nullable();
            $table->foreignId('program_id');
            $table->foreignId('batch_id');
            // $table->longText('batch_name')->nullable();
            $table->longText('discount')->nullable();
            $table->longText('amount')->nullable();
            $table->longText('final_amount')->nullable();
            $table->integer('payment_term')->nullable();
            $table->longText('state')->nullable();
            $table->longText('address')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('payment_type')->default(0);
            $table->longText('txnid')->nullable();
            $table->longText('paid_term')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
