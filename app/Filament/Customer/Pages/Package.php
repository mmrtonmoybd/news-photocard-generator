<?php

namespace App\Filament\Customer\Pages;

use App\Models\Payment;
use Filament\Pages\Page;
use App\Models\Package as PackageModel;

class Package extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $title = 'Available Packages';

    protected static string $view = 'filament.customer.pages.package';

     public $packages;

    public function mount() {
        $this->packages = PackageModel::where('is_active', true)->orderByDesc('price')->get();
    }

    public function payNow($packageId) {
        $package = packageModel::find($packageId);

        if (!$package) {
            return session()->flash('error', 'Package not found');
        }



        $own_transaction_id = 'TRX-' . uniqid();

        Payment::create([
            'own_transaction_id' => $own_transaction_id,
            'payment_method' => 'sslcommerz',
            'amount' => $package->price,
            'package_id' => $package->id,
        ]);

        $post_data = array();
        $post_data['store_id'] = "shope672d37e80da36";
        $post_data['store_passwd'] = "shope672d37e80da36@ssl";
        $post_data['total_amount'] = $package->price;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $own_transaction_id;
        $post_data['success_url'] = route('payment.success');
        $post_data['fail_url'] = route('payment.fail');
        $post_data['cancel_url'] = route('payment.fail');

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = auth()->user()->name;
        $post_data['cus_email'] = auth()->user()->email;
        $post_data['cus_phone'] = "01711111111";
        $post_data['cus_add1'] = "Dhaka";
        $post_data['cus_city'] = "Dhaka";
        $post_data["cus_postcode"] = "1212";
        $post_data['cus_country'] = 'Bangladesh';

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['shipping_method'] = 'No';
        $post_data['num_of_item'] = 1;
        $post_data['weight_of_items'] = '1';
        $post_data['product_name'] = $package->name;
        $post_data['product_category'] = 'Image card';
        $post_data['product_profile'] = 'non-physical-goods';
        $post_data['ship_add1 '] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";

        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


        $content = curl_exec($handle );

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            curl_close( $handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close( $handle);
            return session()->flash('error', 'SSLCommerz API error');
            //exit;
        }

        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );

        \Log::info('SSLCommerz Response:', ['response' => $sslcz]);
         

        if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
        # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
        # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            //echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
            //return header("Location: ". );
           // $this->emit('ssl-pay', );
           return redirect($sslcz['GatewayPageURL']);
            //exit;
        } else {
           return session()->flash('error', 'Payment URL not found');
        }

    }
}
