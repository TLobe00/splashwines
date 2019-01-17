<?php

namespace App\Console\Commands;

use App\Customer;
use App\CustomerBackup;
use App\Includes\ShopifyMultipass;
use App\Order;
use App\OrderItem;
use Illuminate\Console\Command;
use Log;
use GuzzleHttp;
use RocketCode\Shopify\Exceptions\ShopifyException;

class UpdateCustomers extends Command
{
    private $shopify = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:UpdateCustomers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update customers into Shopify';

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
//        $customers = Customer::whereNotNull('shopify_id')->limit(200000)->orderBy('shopify_id', 'DESC');

        $customers = CustomerBackup::whereNotNull('shopify_id')->limit(200000)->orderBy('shopify_id', 'DESC');
//        $customers = CustomerBackup::where('shopify_id','=','5556426053');
//       if( $this->option('customer') )
//            $customers->where( 'customer_id', $this->option('customer') );
        $customers = $customers->get();

        //dd($customers);

        foreach ($customers as $customer) {

//            if($customer->shopify_id == '-2') {
            if($customer->shopify_id == '-4') {
                continue;
            }
            if($customer->updated == 1) {
                continue;
            }

            try {

//                $tags = "";

//                if ( $customer->CustomerLevelID != 2 ) {
//                    if ( $customer->CustomerLevelID == 3 ) {
//                        $tags = "Membership: 01/01/50, Founder Member, Current Member";
//                    } else {
//                        if ( $customer->CustomerLevelID == 6 ) {
//                            $tags = "Membership: 02/01/2018, RSP, Current Member";
//                        } elseif ( $customer->CustomerLevelID == 4 ) {
//                            $tags = "Membership: 02/01/2018, Gold, Current Member";
//                        } elseif ( $customer->CustomerLevelID == 5 ) {
//                            $tags = "Membership: 02/01/2018, Silver, Current Member";
//                        } elseif ( $customer->CustomerLevelID == 7 ) {
//                            $tags = "Membership: 02/01/2018, RSP Gifted, Current Member";
//                        } else {
//                            $tags = "Membership: 02/01/2018, Current Member";
//                        }
//                    }
//                } else {
//                    //$tags = "Inactive Member";
//                    $tags = "Membership: 02/01/2018, Current Member";
//                }

//                $contents = view('templates.customerupdate', [
                $contents = view('templates.customerupdate2', [
                    'customer' => $customer
//                    'customer' => $customer,
//                    'tags' => $tags
                ])->render();

                echo "Updating customer {$customer->shopify_id}\n";

                $contents = str_replace(["\t", "\n"], "", $contents);
                $contents = json_decode($contents, true);
                $contents = json_encode($contents);

                //dd($contents);

                if( empty($contents ) )
                    continue;

                $result = null;
                try {
                    $result = $this->shopify::Customer()->update($contents);
                }
                catch ( ShopifyException $e ) { continue ; }
                catch ( GuzzleHttp\Exception\ConnectException $e ) { continue ; }

                $resultObj = json_decode($result, true);
                if (is_array($resultObj) and array_key_exists('id', $resultObj)) {
                    $customer->updated = 1;
                    $customer->save();
                }
                elseif ( is_array( $resultObj ) and array_key_exists( 'errors', $resultObj) && $resultObj['errors'] == 422 ) {
                    //echo "User " . $customer->{"customer email"} . " already exists.\n";
//                    $customer->shopify_id = -2;
//                    $customer->save();
                }
                else {
                    echo "Error creating for {$customer->customer_id_fromMSSQL}\n";
                    Log::info( $contents );
                }
                usleep(170000);
            } catch (Exception $e) {
//                $customer->shopify_id = -1;
//                $customer->save();
            }
        }
    }
}
