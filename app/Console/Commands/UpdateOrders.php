<?php

namespace App\Console\Commands;

use App\Includes\ShopifyMultipass;
use App\Products;
use App\ProductVariants;
use App\ExtendedPrice;
use App\Category;
use App\Order;
use App\OrderItem;
use App\ProductCategory;
use App\Kits;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class UpdateOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:UpdateOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update orders in Shopify';

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
//        $products = Products::whereNotNull('shopify_id');
//        if( $this->option('order') )


//            $orders = Order::where( 'shopify_id', '5121952965' );
//            $orders = $orders->get();

            $orders = OrderItem::select('orders.shopify_id')->join('orders','orders.order_id','=','orderlineitems.order_id')->where('orderlineitems.Quantity','>','1')->where(function ($query) {
                $query->where('orders.shopify_id', '!=', '-2')
                      ->where('orders.shopify_id', '!=', '-3')
                      ->where('orders.shopify_id', '!=', '-4');
            })->limit(20000)->get();



//        $products = $products->limit(500)->get();
//      echo $orders;
      //dd($orders);

        foreach ($orders as $order) {
            try {

                $contents = view('templates.orderdelete', [
                    'order' => $order
                ])->render();
                //echo $contents;
                //dd($contents);
                echo "Deleting order {$order->shopify_id}\n";

                //echo "Not blank " . $order->order_id . "\n";
            
                $contents = str_replace(["\t", "\n"], "", $contents);
                $contents = json_decode($contents, true);
                    //Log::info( $contents );
                    //print_r($contents);
                $contents = json_encode($contents);
                    //echo $contents;
                if( empty($contents ) )
                    continue;
                
                $saveorder = Order::where( 'shopify_id', $order->shopify_id )->get()->first();

                //dd($saveorder);
                $deleteorder = array('id'=>$order->shopify_id);

                $result = null;
                try {
                    //$result = $this->shopify::Customer()->create($contents);
                    
                    if(!is_null($saveorder)) {
                        $result = $this->shopify::Order()->delete($deleteorder);
                        $saveorder->shopify_id = NULL;
                        $saveorder->save();  
                    } else {
                        continue;
                    }
                
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
