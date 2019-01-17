<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kits extends Model
{
	public $incrementing = false;
	protected $table = 'kits';
	protected $primaryKey = "ProductID";
}
