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
use Illuminate\Support\Facades\DB;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class DeleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:DeleteOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //SELECT name, MIN(id), MAX(id), GROUP_CONCAT(id), COUNT(*) c FROM duplicates WHERE shopify_id IS NOT NULL GROUP BY name HAVING c > 1;
        $orders = DB::table('duplicates')
          ->select(DB::raw('name, MIN(id) as minid, MAX(id) as maxid, GROUP_CONCAT(shopify_id) as ids, COUNT(*) c'))
          ->whereNotNull('shopify_id')
          ->groupBy('name')
          ->having('c', '>', 1)
          ->get()
          //->first();
          ->all();

        //dd($orders);

        foreach ( $orders as $order ) {

          //print $order->name . " - " . $order->minid . " - " . $order->maxid . "\n";
          $tmparry = explode(',',$order->ids);

          foreach ( $tmparry as $key => $val ) {

            if ( ($val == 4921307013) || ($val == 4921306885) ) {
              continue;
            }

            if ( $key == 0 ) {
              print $val . " NOT deleted\n";
            } else {
              print $val . " Deleted\n";
              $sendarray = array('id'=>$val);
              try {
                  //$result = $this->shopify::Customer()->create($contents);
                  $result = $this->shopify::Order()->delete($sendarray);

              }
              catch ( ShopifyException $e ) { Log::info( $order->ids . " - " . $e );continue ; }
              catch ( GuzzleHttp\Exception\ConnectException $e ) { Log::info( $order->ids . " - " . $e );continue ; }
            }
          }

          //dd($order);
        }
    }
}
