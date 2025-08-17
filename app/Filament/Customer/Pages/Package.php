<?php

namespace App\Filament\Customer\Pages;

use App\Models\Package as PackageModel;
use App\Models\Payment;
use Filament\Pages\Page;
use Outerweb\Settings\Models\Setting;
use Filament\Notifications\Notification;

class Package extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $title = 'Available Packages';

    protected static ?string $navigationGroup = 'Packages';

    protected static string $view = 'filament.customer.pages.package';

    public $packages;

    public function mount()
    {
        $this->packages = PackageModel::where('is_active', true)->orderByDesc('price')->get();
    }

    public function payNow($packageId)
    {
        $package = packageModel::find($packageId);

        if (! $package) {
           // return session()->flash('error', 'Package not found');
            Notification::make()->title('Package not found')->danger()
                 ->send();
        }

        $own_transaction_id = 'TRX-'.uniqid();

        Payment::create([
            'own_transaction_id' => $own_transaction_id,
            'payment_method' => 'sslcommerz',
            'amount' => $package->price,
            'package_id' => $package->id,
        ]);

        $post_data = [];
        $post_data['store_id'] = Setting::where('key', 'SSLcommerz.storeid')->value('value');
        $post_data['store_passwd'] = Setting::where('key', 'SSLcommerz.storepassword')->value('value');
        $post_data['total_amount'] = $package->price;
        $post_data['currency'] = 'BDT';
        $post_data['tran_id'] = $own_transaction_id;
        $post_data['success_url'] = route('payment.success');
        $post_data['fail_url'] = route('payment.fail');
        $post_data['cancel_url'] = route('payment.fail');

        // CUSTOMER INFORMATION
        $post_data['cus_name'] = auth()->user()->name;
        $post_data['cus_email'] = auth()->user()->email;
        $post_data['cus_phone'] = '01711111111';
        $post_data['cus_add1'] = 'Dhaka';
        $post_data['cus_city'] = 'Dhaka';
        $post_data['cus_postcode'] = '1212';
        $post_data['cus_country'] = 'Bangladesh';

        // SHIPMENT INFORMATION
        $post_data['ship_name'] = 'Store Test';
        $post_data['shipping_method'] = 'No';
        $post_data['num_of_item'] = 1;
        $post_data['weight_of_items'] = '1';
        $post_data['product_name'] = $package->name;
        $post_data['product_category'] = 'Image card';
        $post_data['product_profile'] = 'non-physical-goods';
        $post_data['ship_add1 '] = 'Dhaka';
        $post_data['ship_add2'] = 'Dhaka';
        $post_data['ship_city'] = 'Dhaka';
        $post_data['ship_state'] = 'Dhaka';
        $post_data['ship_postcode'] = '1000';
        $post_data['ship_country'] = 'Bangladesh';

      if (Setting::where('key', 'SSLcommerz.sandbox')->value('value') === true) {
        $direct_api_url = 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';
      } else {
        $direct_api_url = 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';
      }
        

       $localhost = (Setting::where('key', 'SSLcommerz.localhost')->value('value') === true) ? false : true;

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, $localhost); // KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && ! (curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);

            Notification::make()->title('SSLCommerz API error')->danger()
                 ->send();

            //return session()->flash('error', 'SSLCommerz API error');
            // exit;
        }

        // PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true);

        \Log::info('SSLCommerz Response:', ['response' => $sslcz, Setting::where('key', 'SSLcommerz.storeid')->value('value')]);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != '') {
            // THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            // echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
            // echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
            // return header("Location: ". );
            // $this->emit('ssl-pay', );
            return redirect($sslcz['GatewayPageURL']);
            // exit;
        } else {
            Notification::make()->title('Payment URL not found')->danger()
                 ->send();
            //return session()->flash('error', 'Payment URL not found');
        }

    }
}
