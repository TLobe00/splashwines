<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public $incrementing = false;
	protected $table = 'Category';
	protected $primaryKey = "CategoryID";
	public $timestamps = false;
}
