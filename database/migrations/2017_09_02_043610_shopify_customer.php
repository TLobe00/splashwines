<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id', false)->nullable();
            $table->string('first_name',255)->nullable();
            $table->string('last_name',255)->nullable();
            $table->string('email',255)->nullable();
            $table->integer('accepts_marketing',false)->nullable();
            $table->integer('orders_count',false)->nullable();
            $table->string('state',255)->nullable();
            $table->decimal('total_spent',11,2)->nullable();
            $table->bigInteger('last_order_id',false)->nullable();
            $table->text('note')->nullable();
            $table->integer('verified_email',false)->nullable();
            $table->integer('tax_exempt',false)->nullable();
            $table->string('multipass_identifier',255)->nullable();
            $table->string('phone',255)->nullable();
            $table->text('tags')->nullable();
            $table->string('last_order_name',255)->nullable();

            $table->bigInteger('default_address_shopify_id', false)->nullable();
            $table->string('default_first_name',255)->nullable();
            $table->string('default_last_name',255)->nullable();
            $table->string('default_address1',255)->nullable();
            $table->string('default_phone',255)->nullable();
            $table->string('default_city',255)->nullable();
            $table->string('default_zip',255)->nullable();
            $table->string('default_province',255)->nullable();
            $table->string('default_country',255)->nullable();
            $table->string('default_address2',255)->nullable();
            $table->string('default_company',255)->nullable();
            $table->string('default_name',255)->nullable();
            $table->string('default_country_code',255)->nullable();
            $table->string('default_province_code',255)->nullable();
            $table->timestamps()->nullable();
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
