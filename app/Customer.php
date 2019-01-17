<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	public $incrementing = false;
	protected $table = 'customers';
	protected $primaryKey = "customer_id";
	public $timestamps = false;
}
