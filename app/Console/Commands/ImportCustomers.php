<?php

namespace App\Console\Commands;

use App\Customer;
use App\Includes\ShopifyMultipass;
use App\Order;
use App\OrderItem;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class ImportCustomers extends Command {
	private $shopify = null;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'importer:ImportCustomers
	{--customer= : Only process this customer id. }';

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

		dd('Test');

		$customers = Customer::whereNull('shopify_id');
		if( $this->option('customer') )
			$customers->where( 'customer_id', $this->option('customer') );
		$customers = $customers->orderBy('updated_at', 'DESC')->get();
		foreach ($customers as $customer) {
			try {
				$contents = view('templates.customer', [
					'customer' => $customer,
				])->render();

				echo "Creating customer {$customer->customer_id}\n";

				$contents = str_replace(["\t", "\n"], "", $contents);
				$contents = json_decode($contents, true);
				$contents = json_encode($contents);
				if( empty($contents ) )
					continue;

				$result = null;
				try {
					$result = $this->shopify::Customer()->create($contents);
				}
				catch ( ShopifyException $e ) { continue ; }
				catch ( GuzzleHttp\Exception\ConnectException $e ) { continue ; }

				$resultObj = json_decode($result, true);
				if (is_array($resultObj) and array_key_exists('id', $resultObj)) {
					$customer->shopify_id = $resultObj['id'];
					$customer->save();
				}
				elseif ( is_array( $resultObj ) and array_key_exists( 'errors', $resultObj) && $resultObj['errors'] == 422 ) {
					//echo "User " . $customer->{"customer email"} . " already exists.\n";
					$customer->shopify_id = -2;
					$customer->save();
				}
				else {
					echo "Error creating for {$customer-> customer_id}\n";
					Log::info( $contents );
				}
				usleep(600000);
			} catch (Exception $e) {
				$customer->shopify_id = -1;
				$customer->save();
			}
		}
	}
}
