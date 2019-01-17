<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopifyRefunds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('shopify_id',false)->nullable();
            $table->bigInteger('shopify_order_id',false)->nullable();
            $table->dateTimeTz('created_at')->nullable();
            $table->text('note')->nullable();
            $table->string('restock',255)->nullable();
            $table->bigInteger('user_id',false)->nullable();
            $table->dateTimeTz('processed_at')->nullable();

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
