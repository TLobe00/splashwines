<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyOrderAdjustments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_order_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id',false)->nullable();
            $table->bigInteger('shopify_order_id',false)->nullable();
            $table->bigInteger('shopify_refund_id',false)->nullable();
            $table->decimal('amount',11,2)->nullable();
            $table->decimal('tax_amount',11,2)->nullable();
            $table->string('kind',255)->nullable();
            $table->string('reason',255)->nullable();
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
