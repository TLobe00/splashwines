<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id',false)->nullable();
            $table->bigInteger('shopify_order_id',false)->nullable();
            $table->bigInteger('shopify_parent_id',false)->nullable();
            $table->decimal('amount',11,2)->nullable();
            $table->string('kind',255)->nullable();
            $table->string('gateway',255)->nullable();
            $table->string('status',255)->nullable();
            $table->string('message',255)->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->integer('test',false)->nullable();
            $table->integer('authorization',false)->nullable();
            $table->string('currency',255)->nullable();
            $table->integer('location_id',false)->nullable();
            $table->integer('user_id',false)->nullable();
            $table->integer('device_id',false)->nullable();
            $table->string('receipt_authorization',255)->nullable();
            $table->decimal('receipt_paid_amount',11,2)->nullable();
            $table->integer('error_code',false)->nullable();
            $table->string('source_name',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
