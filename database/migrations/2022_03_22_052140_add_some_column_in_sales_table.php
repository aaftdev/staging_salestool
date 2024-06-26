<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnInSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('mail_status')->nullable()->default(0);
            $table->integer('offer_status')->nullable()->default(0);
            $table->string('location')->nullable();
            $table->foreignId('user_id')->constrained()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('mail_status');
            $table->dropColumn('offer_status');
            $table->dropColumn('location');
            $table->dropColumn('user_id');
        });
    }
}
