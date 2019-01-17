	{
		"id": "{!! $product->shopify_id !!}",
		"body_html": "{{ $product->body_html }}",

		@if ( $kit != "" )
			"metafields": [
				{
					"key": "kit",
					"value": "{{ $kit }}",
					"value_type": "string",
					"namespace": "c_f"
				}
			],
		@endif

		"tags": "{!! $tags !!}"
	}