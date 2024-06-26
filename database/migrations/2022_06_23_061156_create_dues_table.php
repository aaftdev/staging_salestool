<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dues', function (Blueprint $table) {
            $table->id();
            $table->string('VchNo')->nullable();
            $table->dateTime('VchDate')->nullable();
            $table->dateTime('DueDate')->nullable();
            $table->string('LedgerName');
            $table->string('EnrollmentNo');
            $table->string('State');
            $table->string('Batch');
            $table->string('FeeHead');
            $table->string('Amount');
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
        Schema::dropIfExists('dues');
    }
}
