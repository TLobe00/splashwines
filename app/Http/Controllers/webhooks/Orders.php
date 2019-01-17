<?php

namespace App\Http\Controllers\webhooks;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Temptable;
use App\shopify_orders;
use App\shopify_customer;
use App\shopify_order_lineitems;
use App\shopify_order_shipping_lines;
use App\shopify_refunds;
use App\shopify_order_adjustments;
use App\shopify_transactions;
use GuzzleHttp;
use Log;

class Orders extends Controller
{
	public function listen( Request $request ) {
		$data = collect($request->json()->all());

		Log::info( $data );


		//$data = json_decode($request->getContent(), true);
		//$data = $request->json()->all();

		//$data = $request;
		//print_r($data);
		//print $data['id'];  //-----THIS

		$taxlines = $this->implode_all('|', $data['tax_lines']);

		$orderparse = [

			'email' => $data['email'],
			'shopify_id' => $data['id'],
			'closed_at' => empty($data['closed_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['closed_at'])),
			'created_at' => str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['created_at'])),
			'updated_at' => str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['updated_at'])),
			'number' => $data['number'],
			'note' => $data['note'],
			'token' => $data['token'],
			'gateway' => $data['gateway'],
			'test' => $data['test'],
			'total_price' => $data['total_price'],
			'subtotal_price' => $data['subtotal_price'],
			'total_weight' => $data['total_weight'],
			'total_tax' => $data['total_tax'],
			'taxes_included' => $data['taxes_included'],
			'currency' => $data['currency'],
			'financial_status' => $data['financial_status'],
			'confirmed' => $data['confirmed'],
			'total_discounts' => $data['total_discounts'],
			'total_line_items_price' => $data['total_line_items_price'],
			'cart_token' => $data['cart_token'],
			'buyer_accepts_marketing' => $data['buyer_accepts_marketing'],
			'name' => $data['name'],
			'referring_site' => $data['referring_site'],
			'landing_site' => $data['landing_site'],
			'cancelled_at' => empty($data['cancelled_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['cancelled_at'])),
			'cancel_reason' => $data['cancel_reason'],
			'total_price_usd' => $data['total_price_usd'],
			'checkout_token' => $data['checkout_token'],
			'reference' => $data['reference'],
			'user_id' => $data['user_id'],
			'location_id' => $data['location_id'],
			'source_identifier' => $data['source_identifier'],
			'source_url' => $data['source_url'],
			'processed_at' => empty($data['processed_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['processed_at'])),
			'device_id' => $data['device_id'],
			'phone' => $data['phone'],
			'customer_locale' => $data['customer_locale'],
			'app_id' => $data['app_id'],
			'browser_ip' => $data['browser_ip'],
			'landing_site_ref' => $data['landing_site_ref'],
			'order_number' => $data['order_number'],
			'discount_codes' => implode('|',$data['discount_codes']),
			'note_attributes' => implode('|',$data['note_attributes']),
			'payment_gateway_names' => implode('|',$data['payment_gateway_names']),
			'processing_method' => $data['processing_method'],
			'checkout_id' => $data['checkout_id'],
			'source_name' => $data['source_name'],
			'fulfillment_status' => $data['fulfillment_status'],
			'tax_lines' => $taxlines,
			'tags' => $data['tags'],
			'contact_email' => $data['contact_email'],
			'order_status_url' => $data['order_status_url'],

			'shipping_first_name' => $data['shipping_address']['first_name'],
			'shipping_address1' => $data['shipping_address']['address1'],
			'shipping_phone' => $data['shipping_address']['phone'],
			'shipping_city' => $data['shipping_address']['city'],
			'shipping_zip' => $data['shipping_address']['zip'],
			'shipping_province' => $data['shipping_address']['province'],
			'shipping_country' => $data['shipping_address']['country'],
			'shipping_last_name' => $data['shipping_address']['last_name'],
			'shipping_address2' => $data['shipping_address']['address2'],
			'shipping_company' => $data['shipping_address']['company'],
			'shipping_latitude' => $data['shipping_address']['latitude'],
			'shipping_longitude' => $data['shipping_address']['longitude'],
			'shipping_name' => $data['shipping_address']['name'],
			'shipping_country_code' => $data['shipping_address']['country_code'],
			'shipping_province_code' => $data['shipping_address']['province_code'],

			'billing_first_name' => $data['billing_address']['first_name'],
			'billing_address1' => $data['billing_address']['address1'],
			'billing_phone' => $data['billing_address']['phone'],
			'billing_city' => $data['billing_address']['city'],
			'billing_zip' => $data['billing_address']['zip'],
			'billing_province' => $data['billing_address']['province'],
			'billing_country' => $data['billing_address']['country'],
			'billing_last_name' => $data['billing_address']['last_name'],
			'billing_address2' => $data['billing_address']['address2'],
			'billing_company' => $data['billing_address']['company'],
			'billing_latitude' => $data['billing_address']['latitude'],
			'billing_longitude' => $data['billing_address']['longitude'],
			'billing_name' => $data['billing_address']['name'],
			'billing_country_code' => $data['billing_address']['country_code'],
			'billing_province_code' => $data['billing_address']['province_code'],

			'customer_id' => $data['customer']['id']

		];
//		dd($id,$inputparse);
		try {
			shopify_orders::insert($orderparse);
		} catch (Exception $e) {
			log::info('Caught exception: ',  $e->getMessage(), "\n");
		}

		//log::info( $orderparse );


		$customerparse = [
			'shopify_id' => $data['customer']['id'],
			'first_name' => $data['customer']['first_name'],
			'last_name' => $data['customer']['last_name'],
			'email' => $data['customer']['email'],
			'accepts_marketing' => $data['customer']['accepts_marketing'],
			'orders_count' => $data['customer']['orders_count'],
			'state' => $data['customer']['state'],
			'total_spent' => $data['customer']['total_spent'],
			'last_order_id' => $data['customer']['last_order_id'],
			'note' => $data['customer']['note'],
			'verified_email' => $data['customer']['verified_email'],
			'tax_exempt' => $data['customer']['tax_exempt'],
			'multipass_identifier' => $data['customer']['multipass_identifier'],
			'phone' => $data['customer']['phone'],
			'tags' => $data['customer']['tags'],
			'last_order_name' => $data['customer']['last_order_name'],

			'default_address_shopify_id' => $data['customer']['default_address']['id'],
			'default_first_name' => $data['customer']['default_address']['first_name'],
			'default_last_name' => $data['customer']['default_address']['last_name'],
			'default_address1' => $data['customer']['default_address']['address1'],
			'default_phone' => $data['customer']['default_address']['phone'],
			'default_city' => $data['customer']['default_address']['city'],
			'default_zip' => $data['customer']['default_address']['zip'],
			'default_province' => $data['customer']['default_address']['province'],
			'default_country' => $data['customer']['default_address']['country'],
			'default_address2' => $data['customer']['default_address']['address2'],
			'default_company' => $data['customer']['default_address']['company'],
			'default_name' => $data['customer']['default_address']['name'],
			'default_country_code' => $data['customer']['default_address']['country_code'],
			'default_province_code' => $data['customer']['default_address']['province_code'],

			'created_at' => empty($data['customer']['created_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['customer']['created_at'])),
			'updated_at' => empty($data['customer']['updated_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['customer']['updated_at']))
		];

/*
		try {
			shopify_customer::insert($customerparse);
		} catch ( Exception $e ) {
			log::info($e->getMessage());
		}
*/

		$customer = shopify_customer::updateOrCreate(
			['shopify_id'=>$data['customer']['id']],
			$customerparse
		);
		//$user->foo = Input::get('foo');
		//$customer->save();

		collect( $data['shipping_lines'] ?? [] )->each( function($item) use ($data) {
			$shippinglinesparse = [
				'shopify_id' => $item['id'],
				'shopify_order_id' => $data['id'],
				'title' => $item['title'],
				'price' => $item['price'],
				'code' => $item['code'],
				'source' => $item['source'],
				'phone' => $item['phone'],
				'requested_fulfillment_service_id' => $item['requested_fulfillment_service_id'],
				'delivery_category' => $item['delivery_category'],
				'carrier_identifier' => $item['carrier_identifier'],
				'tax_lines' => $this->implode_all('|',$item['tax_lines'])
			];

			shopify_order_shipping_lines::insert($shippinglinesparse);
		});

		collect( $data['line_items'] ?? [] )->each( function($line) use ($data) {
			$orderlinesparse = [
				'shopify_id' => $line['id'],
				'shopify_order_id' => $data['id'],
				'variant_id' => $line['variant_id'],
				'title' => $line['title'],
				'quantity' => $line['quantity'],
				'price' => $line['price'],
				'grams' => $line['grams'],
				'sku' => $line['sku'],
				'variant_title' => $line['variant_title'],
				'vendor' => $line['vendor'],
				'fulfillment_service' => $line['fulfillment_service'],
				'product_id' => $line['product_id'],
				'requires_shipping' => $line['requires_shipping'],
				'taxable' => $line['taxable'],
				'gift_card' => $line['gift_card'],
				'pre_tax_price' => $line['pre_tax_price'],
				'name' => $line['name'],
				'variant_inventory_management' => $line['variant_inventory_management'],
				'properties' => implode('|',$line['properties']),
				'product_exists' => $line['product_exists'],
				'total_discount' => $line['total_discount'],
				'fulfillment_status' => $line['fulfillment_status'],
				'tax_lines' => $this->implode_all('|',$line['tax_lines'])
			];

			shopify_order_lineitems::insert($orderlinesparse);
		});

		//$temptable = Temptable::insert(['description' => $data]);
		//$temptable = new Temptable;
		//$temptable->description = $data2;
		//$temptable->save();
/*
		collect( $data['line_items'] ?? [] )->each( function ( $item ) {
			//Log::info( $item['variant_id'] );
			$voucher = $this->vp->where( 'variant_id', $item['variant_id'] )->first();
			//Log::info( $voucher );
			if( $voucher ) {
				$voucher->redeemed = true;
				$voucher->save();
				$stats = $voucher->profile->stats;
				if( $stats ) {
					$stats->increment( 'redeemed' );
					$stats->save();
				}
			}
		} );
*/

		return response( "OK", 200);
    }

	public function implode_all($glue, $arr){            
	    for ($i=0; $i<count($arr); $i++) {
	        if (@is_array($arr[$i])) 
	            $arr[$i] = $this->implode_all ($glue, $arr[$i]);
	    }            
	    return implode($glue, $arr);
	}

    public function process( Request $request ) {
		$data = $request->all();

		$data2 = implode("|",$data);
		//$temptable = Temptable::insert('description' => $data2);

		$temptable = new Temptable;
		$temptable->description = $data2;
		$temptable->save();
/*
		collect( $data['line_items'] ?? [] )->each( function ( $item ) {
			//Log::info( $item['variant_id'] );
			$voucher = $this->vp->where( 'variant_id', $item['variant_id'] )->first();
			//Log::info( $voucher );
			if( $voucher ) {
				$voucher->redeemed = true;
				$voucher->save();
				$stats = $voucher->profile->stats;
				if( $stats ) {
					$stats->increment( 'redeemed' );
					$stats->save();
				}
			}
		} );
*/

		return response( "OK", 200);
    }

    public function refund( Request $request ) {
		$refund = collect($request->json()->all());

		Log::info( $refund );

//		collect( $data ?? [] )->each( function($refund) use ($data) {
			$refundsparse = [
				'shopify_id' => $refund['id'],
				'shopify_order_id' => $refund['order_id'],
				'created_at' => empty($refund['created_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $refund['created_at'])),
				'note' => $refund['note'],
				'restock' => $refund['restock'],
				'user_id' => $refund['user_id'],
				'processed_at' => empty($refund['processed_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $refund['processed_at']))
			];

			shopify_refunds::insert($refundsparse);

			collect( $refund['refund_line_items'] ?? [] )->each( function($rli) {
				shopify_order_lineitems::where('shopify_id',$rli['line_item_id'])->update(['total_tax' => $rli['total_tax']]);
			});

			collect( $refund['transactions'] ?? [] )->each( function($transaction) {
				$transactionparse = [
					'shopify_id' => $transaction['id'],
					'shopify_order_id' => $transaction['order_id'],
					'shopify_parent_id' => $transaction['parent_id'],
					'amount' => $transaction['amount'],
					'kind' => $transaction['kind'],
					'gateway' => $transaction['gateway'],
					'status' => $transaction['status'],
					'message' => $transaction['message'],
					'created_at' => empty($transaction['created_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $transaction['created_at'])),
					'test' => $transaction['test'],
					'authorization' => $transaction['authorization'],
					'currency' => $transaction['currency'],
					'location_id' => $transaction['location_id'],
					'user_id' => $transaction['user_id'],
					'device_id' => $transaction['device_id'],
					'receipt_paid_amount' => $transaction['receipt']['paid_amount'],
					'error_code' => $transaction['error_code'],
					'source_name' => $transaction['source_name']
				];

				shopify_transactions::insert($transactionparse);
			});

			collect( $refund['order_adjustments'] ?? [] )->each( function($order_adjustment) {
				$orderadjustmentparse = [
					'shopify_id' => $order_adjustment['id'],
					'shopify_order_id' => $order_adjustment['order_id'],
					'shopify_refund_id' => $order_adjustment['refund_id'],
					'amount' => $order_adjustment['amount'],
					'tax_amount' => $order_adjustment['tax_amount'],
					'kind' => $order_adjustment['kind'],
					'reason' => $order_adjustment['reason']
				];

				shopify_order_adjustments::insert($orderadjustmentparse);
			});

//		});

    	return response( "OK", 200);
    }

    public function cancel( Request $request ) {
		$data = collect($request->json()->all());

		Log::info( $data );

		collect( $data['refunds'] ?? [] )->each( function($refund) use ($data) {
			$refundsparse = [
				'shopify_id' => $refund['id'],
				'shopify_order_id' => $refund['order_id'],
				'created_at' => empty($refund['created_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $refund['created_at'])),
				'note' => $refund['note'],
				'restock' => $refund['restock'],
				'user_id' => $refund['user_id'],
				'processed_at' => empty($refund['processed_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $refund['processed_at'])),
				'customer_id' => $data['customer']['id']
			];

			shopify_refunds::insert($refundsparse);

			collect( $refund['refund_line_items'] ?? [] )->each( function($rli) {
				shopify_order_lineitems::where('shopify_id',$rli['line_item_id'])->update(['total_tax' => $rli['total_tax']]);
			});

			collect( $refund['transactions'] ?? [] )->each( function($transaction) {
				$transactionparse = [
					'shopify_id' => $transaction['id'],
					'shopify_order_id' => $transaction['order_id'],
					'shopify_parent_id' => $transaction['parent_id'],
					'amount' => $transaction['amount'],
					'kind' => $transaction['kind'],
					'gateway' => $transaction['gateway'],
					'status' => $transaction['status'],
					'message' => $transaction['message'],
					'created_at' => empty($transaction['created_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $transaction['created_at'])),
					'test' => $transaction['test'],
					'authorization' => $transaction['authorization'],
					'currency' => $transaction['currency'],
					'location_id' => $transaction['location_id'],
					'user_id' => $transaction['user_id'],
					'device_id' => $transaction['device_id'],
					'receipt_authorization' => $transaction['receipt']['authorization'],
					'error_code' => $transaction['error_code'],
					'source_name' => $transaction['source_name']
				];

				shopify_transactions::insert($transactionparse);
			});

			collect( $refund['order_adjustments'] ?? [] )->each( function($order_adjustment) {
				$orderadjustmentparse = [
					'shopify_id' => $order_adjustment['id'],
					'shopify_order_id' => $order_adjustment['order_id'],
					'shopify_refund_id' => $order_adjustment['refund_id'],
					'amount' => $order_adjustment['amount'],
					'tax_amount' => $order_adjustment['tax_amount'],
					'kind' => $order_adjustment['kind'],
					'reason' => $order_adjustment['reason']
				];

				shopify_order_adjustments::insert($orderadjustmentparse);
			});

		});

		$orderupdateparse = [
			'updated_at' => empty($data['updated_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['updated_at'])),
			'cancelled_at' => empty($data['cancelled_at']) ? NULL : str_replace("T", " ", preg_replace('/-\d{2}:\d{2}/', '', $data['cancelled_at'])),
			'cancel_reason' => $data['cancel_reason']
		];
		shopify_orders::where('shopify_id',$data['id'])->update($orderupdateparse);

    	return response( "OK", 200);
    }
}
