<?php

namespace App\Console\Commands;

use App\Includes\ShopifyMultipass;
use App\Products;
use App\ProductVariants;
use App\ExtendedPrice;
use App\Category;
use App\ProductCategory;
use App\Kits;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class UpdateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:UpdateProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products into Shopify';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->shopify = new \RocketCode\Shopify\Client;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $products = Products::whereNotNull('shopify_id');
//        if( $this->option('order') )
//            $orders->where( 'order_id', $this->option('order') );
        $products = $products->limit(500)->get();
//      echo $orders;

        foreach ($products as $product) {
            try {
 //               $variants = ProductVariants::where('ProductIDfromMSSQL', $product->ProductIDfromMSSQL)->where('published',1)->get()->all();

                $categories = ProductCategory::where('ProductID', $product->ProductIDfromMSSQL)->get()->all();
                //dd($categories);
                $tagtmp = '';
                foreach ($categories as $category) {
                    $categoryarray = Category::where('CategoryID', $category->CategoryID)->where('Published',1)->get()->first();
                    if (count($categoryarray) > 0) {
                        if ( $tagtmp == '' ) {
                            $tagtmp = $categoryarray->Name;
                        }

                        if ( $categoryarray->ParentCategoryID != 0 ) {
                            $categoryarray2 = Category::where('CategoryID', $categoryarray->ParentCategoryID)->get()->first();

                            $tagtmp = $tagtmp . ', ' . $categoryarray2->Name . ':' . $categoryarray->Name;
                        } else {
                            $tagtmp = $tagtmp . ', ' . $categoryarray->Name;
                        }
                        //print $categoryarray->Name;
                        //print "\n";
                        //dd($categoryarray->Name);
                    }
                }
                //dd($product->kit);
                $kittmp = '';
                if ( $product->kit != 0 ) {
                    $kits = Kits::where('ProductID', $product->ProductIDfromMSSQL)->get()->all();
                    foreach ($kits as $kit) {
                        $bottle = "Bottle";
                        if ( $kit->InventoryQuantityDelta > 1 ) { $bottle = "Bottles"; }
                        $kittmp = $kittmp . "<li>" . $kit->Name . "<br>" . $kit->InventoryQuantityDelta . " " . $bottle . "</li>";
                    }
                }
                //dd($kittmp);

                $contents = view('templates.productupdate-old', [
                    'product' => $product,
                    'tags' => $tagtmp,
                    'kit' => $kittmp
                ])->render();
                //echo $contents;
                //dd($contents);
                echo "Updating product {$product->ProductIDfromMSSQL}\n";

                //echo "Not blank " . $order->order_id . "\n";
            
                $contents = str_replace(["\t", "\n"], "", $contents);
                $contents = json_decode($contents, true);
                    //Log::info( $contents );
                    //print_r($contents);
                $contents = json_encode($contents);
                    //echo $contents;
                if( empty($contents ) )
                    continue;
                
                $result = null;
                try {
                    //$result = $this->shopify::Customer()->create($contents);
                    $result = $this->shopify::Product()->update($contents);
                
                }
                catch ( ShopifyException $e ) { continue ; }
                catch ( GuzzleHttp\Exception\ConnectException $e ) { continue ; }
                
                $resultObj = json_decode($result, true);
                //dd( $result );
 //               if (is_array($resultObj) and array_key_exists('id', $resultObj)) {
 //                   $product->shopify_id = $resultObj['id'];
 //                   $product->save();
 //               } else {
 //                   echo "Error creating for {$order->order_id}\n";
 //                   Log::info( $contents );
 //                   $product->shopify_id = -2;
 //                   $product->save();
 //               }
                usleep(600000);
            } catch (Exception $e) { //Exception $e
 //               $product->shopify_id = -1;
 //               $product->save();
            }
        }

    }
}
