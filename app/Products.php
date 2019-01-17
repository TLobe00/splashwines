<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
	public $incrementing = false;
	protected $table = 'shopify_products';
	protected $primaryKey = "ProductIDfromMSSQL";
	public $timestamps = false;
}
