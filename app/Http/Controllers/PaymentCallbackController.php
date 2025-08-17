<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Filament\Customer\Pages\SuccessPay;
use App\Filament\Customer\Pages\FailPay;
use Illuminate\Support\Carbon;

class PaymentCallbackController extends Controller
{
    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $bank = $request->input('bank_tran_id');

        //var_dump($request->all());
        $payment = Payment::where('own_transaction_id', $tran_id)->first();
        
        if ($payment) {
            if ($payment->amount == $amount) {
                $package = $payment->package;


                $carbon = Carbon::now();
                $day = $package->days;
                //$daysToAdd = 5;

// Add days
               $newDate = $carbon->addDays($day);

// Format as Y-m-d
                  $formattedDate = $newDate->format('Y-m-d');

                  User::where('id', $payment->user_id)->update([
                    'package_expiry_date' => $formattedDate,
                  ]);

                $payment->update([
                    'status' => 'success',
                    'transaction_id' => $bank,
                ]);
            }
        }
        // Redirect to Filament Page after payment
        return redirect(SuccessPay::getUrl(['tran_id' => $tran_id, 'amount' => $amount], true, 'customer'));
    }

    public function fail(Request $request)
    {

        return redirect(FailPay::getUrl([], true, 'customer'));
    }
}
