<x-filament-panels::page>
<center>
    <h2 class="text-2xl font-bold mb-4">Payment Successful!</h2>
    <p class="text-gray-700 mb-6">Thank you for your payment. Your transaction has been completed successfully.</p>
    
    <div class="bg-white rounded-lg shadow-md p-6 max-w-md mx-auto">
        <h3 class="text-lg font-semibold mb-2">Transaction Details</h3>
        <ul class="list-disc pl-5 text-gray-600">
            <li>Transaction ID: {{ request('tran_id') }}</li>
            <li>Amount: TK. {{ request('amount') }}</li>
            <li>Date: {{ now()->format('Y-m-d H:i:s') }}</li>
        </ul>
    </div>
</center>
</x-filament-panels::page>
