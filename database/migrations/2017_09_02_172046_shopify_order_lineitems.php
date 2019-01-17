<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyOrderLineitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_order_lineitems', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id',false)->nullable();
            $table->bigInteger('shopify_order_id',false)->nullable();
            $table->bigInteger('variant_id',false)->nullable();
            $table->string('title',255)->nullable();
            $table->integer('quantity',false)->nullable();
            $table->decimal('price',11,2)->nullable();
            $table->integer('grams',false)->nullable();
            $table->string('sku',255)->nullable();
            $table->string('variant_title',255)->nullable();
            $table->string('vendor',255)->nullable();
            $table->string('fulfillment_service',255)->nullable();
            $table->bigInteger('product_id',false)->nullable();
            $table->integer('requires_shipping',false)->nullable();
            $table->integer('taxable',false)->nullable();
            $table->integer('gift_card',false)->nullable();
            $table->decimal('pre_tax_price',11,2)->nullable();
            $table->string('name',255)->nullable();
            $table->string('variant_inventory_management',255)->nullable();
            $table->text('properties')->nullable();
            $table->integer('product_exists',false)->nullable();
            $table->decimal('total_discount',11,2)->nullable();
            $table->string('fulfillment_status',255)->nullable();
            $table->text('tax_lines')->nullable();
            $table->decimal('total_tax',11,2)->nullable();
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
