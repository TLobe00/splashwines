<?php

namespace App\Console\Commands;

use App\Includes\ShopifyMultipass;
use App\Products;
use App\ProductVariants;
use App\ExtendedPrice;
use App\Category;
use App\ProductCategory;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class ImportProducts extends Command
{
    private $shopify = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:ImportProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products into Shopify';

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

        $products = Products::whereNull('shopify_id');
//        if( $this->option('order') )
//            $orders->where( 'order_id', $this->option('order') );
        $products = $products->where('published',1)->limit(500)->get();
//      echo $orders;

        foreach ($products as $product) {
            try {
                $variants = ProductVariants::where('ProductIDfromMSSQL', $product->ProductIDfromMSSQL)->where('published',1)->get()->all();

                $categories = ProductCategory::where('ProductID', $product->ProductIDfromMSSQL)->get()->all();
                //dd($categories);
                $tagtmp = '';
                foreach ($categories as $category) {
                    $categoryarray = Category::where('CategoryID', $category->CategoryID)->where('Published',1)->get()->first();
                    if (count($categoryarray) > 0) {
                        if ( $tagtmp == '' ) {
                            $tagtmp = $categoryarray->Name;
                        }
                        $tagtmp = $tagtmp . ', ' . $categoryarray->Name;
                        //print $categoryarray->Name;
                        //print "\n";
                        //dd($categoryarray->Name);
                    }
                }
                //dd($tagtmp);

                $contents = view('templates.products', [
                    'product' => $product,
                    'variants' => $variants,
                    'tags' => $tagtmp
                ])->render();
                //echo $contents;
                echo "Creating product {$product->ProductIDfromMSSQL}\n";

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
                    $result = $this->shopify::Product()->create($contents);
                
                }
                catch ( ShopifyException $e ) { continue ; }
                catch ( GuzzleHttp\Exception\ConnectException $e ) { continue ; }
                
                $resultObj = json_decode($result, true);
                //dd( $result );
                if (is_array($resultObj) and array_key_exists('id', $resultObj)) {
                    $product->shopify_id = $resultObj['id'];
                    $product->save();
                } else {
                    echo "Error creating for {$order->order_id}\n";
                    Log::info( $contents );
                    $product->shopify_id = -2;
                    $product->save();
                }
                usleep(600000);
            } catch (Exception $e) { //Exception $e
                $product->shopify_id = -1;
                $product->save();
            }
        }

    }
}
