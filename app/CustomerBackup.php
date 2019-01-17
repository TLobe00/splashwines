<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBackup extends Model
{
	public $incrementing = false;
	protected $table = 'customers_from_shopify';
	protected $primaryKey = "id";
	public $timestamps = false;
}
