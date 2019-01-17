<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',255)->nullable();
            $table->bigInteger('shopify_id',false)->nullable();
            $table->dateTimeTz('closed_at')->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->dateTimeTz('updated_at')->nullable();
            $table->bigInteger('number',false)->nullable();
            $table->text('note')->nullable();
            $table->string('token',255)->nullable();
            $table->string('gateway',255)->nullable();
            $table->bigInteger('test',false)->nullable();
            $table->decimal('total_price',11,2)->nullable();
            $table->decimal('subtotal_price',11,2)->nullable();
            $table->decimal('total_weight',11)->nullable();
            $table->decimal('total_tax',11,2)->nullable();
            $table->bigInteger('taxes_included',false)->nullable();
            $table->string('currency',255)->nullable();
            $table->string('financial_status',255)->nullable();
            $table->string('confirmed',255)->nullable();
            $table->decimal('total_discounts',11,2)->nullable();
            $table->decimal('total_line_items_price',11,2)->nullable();
            $table->string('cart_token',255)->nullable();
            $table->bigInteger('buyer_accepts_marketing',false)->nullable();
            $table->string('name',255)->nullable();
            $table->text('referring_site')->nullable();
            $table->text('landing_site')->nullable();
            $table->dateTimeTz('cancelled_at')->nullable();
            $table->string('cancel_reason',255)->nullable();
            $table->decimal('total_price_usd',11,2)->nullable();
            $table->string('checkout_token',255)->nullable();
            $table->text('reference')->nullable();
            $table->bigInteger('user_id',false)->nullable();
            $table->bigInteger('location_id',false)->nullable();
            $table->text('source_identifier')->nullable();
            $table->text('source_url')->nullable();
            $table->dateTimeTz('processed_at')->nullable();
            $table->bigInteger('device_id',false)->nullable();
            $table->string('phone',255)->nullable();
            $table->string('customer_locale',255)->nullable();
            $table->bigInteger('app_id',false)->nullable();
            $table->string('browser_ip',255)->nullable();
            $table->text('landing_site_ref')->nullable();
            $table->bigInteger('order_number',false)->nullable();
            $table->text('discount_codes')->nullable();
            $table->text('note_attributes')->nullable();
            $table->text('payment_gateway_names')->nullable();
            $table->string('processing_method',255)->nullable();
            $table->bigInteger('checkout_id',false)->nullable();
            $table->string('source_name',255)->nullable();
            $table->string('fulfillment_status',255)->nullable();
            $table->text('tax_lines')->nullable();
            $table->text('tags')->nullable();
            $table->string('contact_email',255)->nullable();
            $table->text('order_status_url')->nullable();

            $table->string('shipping_first_name',255)->nullable();
            $table->string('shipping_address1',255)->nullable();
            $table->string('shipping_phone',255)->nullable();
            $table->string('shipping_city',255)->nullable();
            $table->string('shipping_zip',255)->nullable();
            $table->string('shipping_province',255)->nullable();
            $table->string('shipping_country',255)->nullable();
            $table->string('shipping_last_name',255)->nullable();
            $table->string('shipping_address2',255)->nullable();
            $table->string('shipping_company',255)->nullable();
            $table->string('shipping_latitude',255)->nullable();
            $table->string('shipping_longitude',255)->nullable();
            $table->string('shipping_name',255)->nullable();
            $table->string('shipping_country_code',255)->nullable();
            $table->string('shipping_province_code',255)->nullable();

            $table->string('billing_first_name',255)->nullable();
            $table->string('billing_address1',255)->nullable();
            $table->string('billing_phone',255)->nullable();
            $table->string('billing_city',255)->nullable();
            $table->string('billing_zip',255)->nullable();
            $table->string('billing_province',255)->nullable();
            $table->string('billing_country',255)->nullable();
            $table->string('billing_last_name',255)->nullable();
            $table->string('billing_address2',255)->nullable();
            $table->string('billing_company',255)->nullable();
            $table->string('billing_latitude',255)->nullable();
            $table->string('billing_longitude',255)->nullable();
            $table->string('billing_name',255)->nullable();
            $table->string('billing_country_code',255)->nullable();
            $table->string('billing_province_code',255)->nullable();

            $table->bigInteger('customer_id',false)->nullable();

            $table->integer('passed_to_vingo',false)->nullable();
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
