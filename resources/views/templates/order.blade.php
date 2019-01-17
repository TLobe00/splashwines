	{
		"email": "{!!  $order->email !!}",
		"created_at": "{!!  $order->created_at !!}",
		"note": "Imported from Symphony",
		"test": false,
		"total_price": "{!!  $order->total_price !!}",
		"subtotal_price": "{!!  $order->subtotal_price !!}",
		"total_weight": 0,
		"total_tax": "{!!  $order->total_tax !!}",
		"taxes_included": false,
		"currency": "USD",
		"financial_status": "paid",
		"total_discounts": "{!!  $order->total_discounts !!}",
		"total_line_items_price": "{!!  $order->total_line_items_price !!}",
		"buyer_accepts_marketing": true,
		"name": "Import-{!!  $order->order_id !!}",
		"total_price_usd": "{!!  $order->total_price !!}",

		"fulfillment_status" : "fulfilled",

		"processed_at": "{!!  $order->created_at !!}",

		@if( $order->discount_codes != "" && $order->subtotal_price != 0 )
			"discount_codes": [
				{
					"code": "{!!  $order->discount_codes !!}",
					"amount": "{!! abs( ( $order->total_discounts ) ) !!}",
					"type": "amount"
				}
			],
		@endif

		"payment_gateway_names": [
			"bogus"
		],
		"processing_method": "manual",
		"source_name": "symphony",
		"tags": "symphony, imported",
		"contact_email": "{!!  $order->email !!}",

		"billing_address": {
			"address1": "{{  empty($order->billing_address1) ?  $order->shipping_address1 : $order->billing_address1 }}",
			"city": "{!!  empty($order->billing_city) ? $order->shipping_city : $order->billing_city !!}",
			"zip": "{!!  empty($order->billing_zip) ? $order->shipping_zip : $order->billing_zip !!}",
			"company": "{!!  empty($order->billing_company) ? $order->shipping_company : $order->billing_company !!}",
			"name": "{{  empty($order->billing_name) ? $order->customer_firstname : $order->billing_name }}",
			"country": "{!!  empty($order->billing_country) ? $order->shipping_country : $order->billing_country !!}",
			"province_code": "{!!  empty($order->billing_state) ? $order->shipping_state : $order->billing_state !!}"
		},

		"shipping_address": {
			"address1": "{{ $order->shipping_address1 }}",
			"city": "{!!  $order->shipping_city !!}",
			"zip": "{!!  $order->shipping_zip !!}",
			"country": "{!!  $order->shipping_country !!}",
			"name": "{{  $order->shipping_firstname }} {{  $order->shipping_lastname }}",
			"province_code": "{!!  $order->shipping_state !!}"
		},

		"customer": {
			"email": "{!!  $order->email !!}",
			"updated_at": "{!!  $order->updated_at !!}"
		},

		"line_items": [
			@foreach( $order_items as $item )
				{
					"title": "{!!  $item->OrderedProductName !!}",
					"quantity": "{!!  $item->Quantity !!}",
					@if( $item->OrderedProductSalePrice != '0.00' && !is_null($item->OrderedProductSalePrice)  )
						"price": "{!!  $item->OrderedProductSalePrice !!}",
					@elseif ( $item->OrderedProductExtendedPrice != '0.00' && !is_null($item->OrderedProductExtendedPrice) )
						"price": "{!!  $item->OrderedProductExtendedPrice !!}",
					@elseif ( $item->OrderedProductRegularPrice != '0.00' && !is_null($item->OrderedProductRegularPrice) )
						"price": "{!!  $item->OrderedProductRegularPrice  !!}",
					@else
						"price": "{!!  $item->OrderedProductPrice / $item->Quantity  !!}",
					@endif
					"sku": "{!!  $item->OrderedProductSKU !!}",
					"taxable" : "false"
				}
				@unless ( $loop->last )
					,
				@endunless
			@endforeach
		]
	}