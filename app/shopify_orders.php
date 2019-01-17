<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shopify_orders extends Model
{
    protected $table = 'shopify_orders';

    public function lineitems () {
    	return $this->hasMany( 'App\shopify_order_lineitems', 'shopify_order_id', 'shopify_id' );
    }

    public function shippinglines () {
    	return $this->hasMany( 'App\shopify_order_shipping_lines', 'shopify_order_id', 'shopify_id' );
    }

//    public function customer () {
//    	return $this->hasOne( 'App\shopify_customer', 'shopify_id', 'customer_id' );
//    }
}
