<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Includes\ShopifyMultipass;
use App\Products;
use App\ProductVariants;
use App\ExtendedPrice;
use App\Category;
use App\ProductCategory;
use App\Kits;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class getProducts extends Controller
{
    public function all() {

	    $this->shopify = new \RocketCode\Shopify\Client;

	    $productHandle = $this->shopify::Product();
	        $args = [
	            'filters' => [
	                'limit' => 250,
	            ],
	            'fields' => [
	                'id','variants','name'
	            ]
	        ];
	    $products = $productHandle->all( $args );
	    $prodArr = json_decode( $products, true );
	    //dd($prodArr[0]['variants'][0]['id']);

	    //dd($prodArr[0]['variants']);

	    foreach ($prodArr as $product) {
	    	//print $product['variants'][0]['id'] . "<br>";
	    	foreach ($product['variants'] as $variant) {
	    		$productid = Products::where('shopify_id', $variant['product_id'])->get(['ProductIDfromMSSQL'])->first();
	    		if ( $productid ) {
	    			$variantid = ProductVariants::where('ProductIDfromMSSQL', $productid->ProductIDfromMSSQL)->get()->first();
	    			print "Updated: " . $productid->ProductIDfromMSSQL . " - " . $variantid->idfromMSSQL . " - " . $variant['id'] . "<br>";
	    			$variantid->shopify_id = $variant['id'];
	    			$variantid->shopify_product_id = $variant['product_id'];
//	    			print $variantid->shopify_id . " - " . $variantid->shopify_product_id . "<br><br>";
					$variantid->save();
	    		}
	    	}
	    }
	    //dd($prodArr[0]['variants']);
	}
}
