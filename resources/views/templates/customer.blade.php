{
	"email" : "{!! $customer->{"email"} !!}",
	"total_spent" : "{!! $customer->{"total_spent"} ?? 0 !!}",
	"orders_count" : "{!! $customer->{"orders_count"} ?? 0 !!}",
	"tags": "symphony, imported",
	"first_name" : "{!! $customer->{"first_name"} ?? '' !!}",
	"last_name" : "{!! $customer->{"last_name"} ?? '' !!}"
}