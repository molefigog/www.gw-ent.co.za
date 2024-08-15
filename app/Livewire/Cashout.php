<?php

namespace App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
class Cashout extends Component
{
    #[Validate('required|numeric')]
    public $mobileNumber;

    #[Validate('required|numeric|min:1')]
    public $amount;

    public function pay()
    {
        // 1. Check user balance
        $user = auth()->user(); // Assuming the user is authenticated
        $userBalance = $user->balance; // Adjust this to match your balance field
        $amountToDeduct = $this->amount;

        if ($userBalance < $amountToDeduct) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        // 2. Deduct funds from user balance
        $user->balance -= $amountToDeduct;
        $user->save();

        // 3. Prepare API request
        $baseUrl = 'https://api.paylesotho.co.ls';
        $merchantid = config('payments.mpesa_sc');
        $token = config('payments.token');

        $client = new Client();
        $paymentApiUrl = $baseUrl . '/api/mpesa-cashout/cashout';
        $paymentApiData = [
            'merchantNumber' => $merchantid,
            'amount' => $amountToDeduct,
            'phoneNumber' => $this->mobileNumber,
        ];

        Log::info('Payment API Request: ' . json_encode([
            'url' => $paymentApiUrl,
            'data' => $paymentApiData,
        ]));

        // 4. Make API request
        try {
            $response = $client->post($paymentApiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $paymentApiData,
            ]);

            $responseData = json_decode($response->getBody(), true);
            Log::info('Payment API Response: ' . json_encode($responseData));

            // 5. Handle successful payment
            if (isset($responseData['status_code']) && $responseData['status_code'] === 'INS-0') {
                return response()->json(['success' => 'Payment processed successfully']);
            } else {
                // 6. Handle failed payment and refund balance
                $user->balance += $amountToDeduct;
                $user->save();
                session()->flash('error', 'Payment failed.');
                $this->dispatch('paymentFailed');
                return response()->json(['error' => 'Payment failed', 'response' => $responseData], 400);
            }
        } catch (\Exception $e) {
            // 7. Handle exceptions and refund balance
            $user->balance += $amountToDeduct;
            $user->save();

            Log::error('Payment API Error: ' . $e->getMessage());
            session()->flash('error2', 'Payment API Error.');

            return response()->json(['error' => 'Payment processing failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function render()
    {
        $credits = Auth::user()->balance;

        return view('livewire.cashout',[
        'credits' => $credits,
       ]);
    }
}
