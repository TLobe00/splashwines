<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
	public $incrementing = false;
	protected $table = 'ProductCategory';
	protected $primaryKey = "ProductID";
	public $timestamps = false;

}
