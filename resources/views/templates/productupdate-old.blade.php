	{
		@if ( $kit != "" )
			"metafields": [
				{
					"key": "kit2",
					"value": "{!! $kit !!}",
					"value_type": "string",
					"namespace": "c_f"
				}
			],
		@endif
		"id": "{!! $product->shopify_id !!}",
		"images": [
			{
				"src": "https://www.splashwines.com/images/Product/large/{!! $product->ProductIDfromMSSQL !!}.jpg"
			}
		]
	}