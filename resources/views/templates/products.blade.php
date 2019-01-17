	{
		"created_at": "{!!  $product->created_at !!}",
		"product_type": "{!! $product->product_type !!}",
		"tags": "{!! $tags !!}",
		"title": "{!! $product->title !!}",
		"updated_at": "{!! $product->updated_at !!}",

		"variants": [
			@foreach( $variants as $item )
				{
					"compare_at_price": "{!!  $item->compare_at_price !!}",
					"created_at": "{!!  $item->created_at !!}",
					"weight": "{!! $item->weight !!}",
					"weight_unit": "{!! $item->weight_unit !!}",

					@if( $item->extendedprice != "" )
						"price": "{!! $item->extendedprice->Price !!}",
					@else
						"price": "{!! $item->price !!}",
					@endif

					@if( $item->sku == "" || $item->sku == '-3' )
						"sku": "{!! $product->sku !!}",
					@else
						"sku": "{!! $item->sku !!}",
					@endif
					"taxable": false,
					"title": "{!! $item->title !!}",
					"updated_at": "{!! $item->updated_at !!}"
				}
				@unless ( $loop->last )
					,
				@endunless
			@endforeach
		]
	}