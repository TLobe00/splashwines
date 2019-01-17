<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shopify_order_shipping_lines extends Model
{
    protected $table = 'shopify_order_shipping_lines';
    protected $with = [
    	'shopify_orders'
    ];

	public function profile() {
		return $this->belongsTo('App\shopify_orders', 'shopify_order_id', 'shopify_id' );
	}
}
