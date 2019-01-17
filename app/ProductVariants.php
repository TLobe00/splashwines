<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    public $incrementing = false;
    protected $table = 'shopify_product_variants';
    protected $primaryKey = 'mysqlid';

     /**
     * Get the extended price.
     */
    public function extendedprice()
    {
        return $this->hasOne('App\ExtendedPrice', 'VariantID', 'idfromMSSQL');
    }
}
