<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyOrderShippingLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_order_shipping_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id',false)->nullable();
            $table->bigInteger('shopify_order_id',false)->nullable();
            $table->string('title',255)->nullable();
            $table->decimal('price',11,2)->nullable();
            $table->string('code',255)->nullable();
            $table->string('source',255)->nullable();
            $table->string('phone',255)->nullable();
            $table->bigInteger('requested_fulfillment_service_id',false)->nullable();
            $table->string('delivery_category',255)->nullable();
            $table->string('carrier_identifier',255)->nullable();
            $table->text('tax_lines')->nullable();
            $table->unique(['shopify_id']);
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
