<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RavePaymentRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rave_payment_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('environment', 10);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10);
            $table->tinyInteger('processed')->default(0);
            $table->string('reference', 100);
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
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
        Schema::dropIfExists('rave_payment_request');
    }
}
