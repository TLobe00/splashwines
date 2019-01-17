<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shopify_product_kits extends Model
{
    public $incrementing = false;
    protected $table = 'shopify_product_kits';
    protected $primaryKey = ['id'];
}
