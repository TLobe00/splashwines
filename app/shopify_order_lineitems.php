<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shopify_order_lineitems extends Model
{
    protected $table = 'shopify_order_lineitems';
    protected $with = [
    	'shopify_orders'
    ];
    public $timestamps  = false ;

	public function profile() {
		return $this->belongsTo('App\shopify_orders', 'shopify_order_id', 'shopify_id' );
	}
}
