<?php

namespace App\Console\Commands;

use App\Includes\ShopifyMultipass;
use App\Order;
use App\OrderItem;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class ImportOrders extends Command {
	private $shopify = null;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'importer:ImportOrders
	{--order= : Only process this order id. }';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import orders into Shopify';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->shopify = new \RocketCode\Shopify\Client;
		parent::__construct();
	}

	private function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}

/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$orders = Order::whereNull('shopify_id');
//		$orders = Order::where('order_id','305848');
//		if( $this->option('order') )
//			$orders->where( 'order_id', $this->option('order') );
		$orders = $orders->limit(100000)->get();
//		echo $orders;

		foreach ($orders as $order) {
			try {
				$items = OrderItem::where('order_id', $order->order_id)->get()->all();
				$contents = view('templates.order', [
					'order' => $order,
					'order_items' => $items,
					'newPassword' => $this->generateRandomString()
				])->render();
				//echo $contents;
				echo "Creating order {$order->order_id}\n";
//				if (count($items) > 0) {
					//echo "Not blank " . $order->order_id . "\n";
				
					$contents = str_replace(["\t", "\n"], "", $contents);
					$contents = json_decode($contents, true);
					//Log::info( $contents );
//					print_r($contents);
					$contents = json_encode($contents);
//					echo $contents;

					//dd($contents);


					if( empty($contents ) )
						continue;
					
					$result = null;
					try {
					//$result = $this->shopify::Customer()->create($contents);
						$result = $this->shopify::Order()->create($contents);
					
					}
					catch ( ShopifyException $e ) { continue ; }
					catch ( GuzzleHttp\Exception\ConnectException $e ) { continue ; }
					
					$resultObj = json_decode($result, true);
					//dd( $result );
					if (is_array($resultObj) and array_key_exists('id', $resultObj)) {
						$order->shopify_id = $resultObj['id'];
						$order->save();
					} else {
						echo "Error creating for {$order->order_id}\n";
						Log::info( $contents );
						$order->shopify_id = -2;
						$order->save();
					}
					usleep(600000);
//				}
			} catch (Exception $e) {
				$order->shopify_id = -1;
				$order->save();
			}
		}

	}
}
