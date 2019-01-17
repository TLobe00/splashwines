<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Includes\ShopifyMultipass;

use App\Customer;
use Log;
use GuzzleHttp;

class Multipass extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'importer:Multipass';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate Multipass accounts for customers.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$orders = Customer::whereNotNull('shopify_id')
			->where( 'shopify_id', '!=', -1 )
			->where( 'shopify_id', '!=', -2 )
			->where( 'multipass', false );
		$orders = $orders->get();
		foreach ($orders as $order) {
			$multipass = new ShopifyMultipass();
			$customer_data = [
				'email' => $order->{"customer email"},
			];
			$token = $multipass->generate_token($customer_data);
			$res = null;
			try {
				$client = new GuzzleHttp\Client(['http_errors' => false]);
				$url = "https://" . env( 'SHOP_DOMAIN' ) . "/account/login/multipass/$token";
				$res = $client->request('GET', $url );
			}
			catch ( GuzzleHttp\Exception\ConnectException $e ) {
				continue;
			}
			catch ( \Exception $e ){
				continue;
			}

			$this->info( "Email: " . $order->{"customer email"} . "\t" . $res->getStatusCode() );
			$order->multipass = true;
			$order->save();
		}
	}
}
