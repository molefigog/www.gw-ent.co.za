<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use App\Mail\WebhookReceived;
use Illuminate\Support\Facades\Mail;
use  App\Http\Controllers\TanzaniaController;
use Exception;

class AndroidApiController extends Controller
{
    public function makePayment(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'msisdn' => 'required|string',
            'transactionid' => 'required|string',
            'thirdpartyconversationID' => 'required|string',
            'transactionDescription' => 'required|string',
        ]);

        // Initialize the MpesaTanzania class with the necessary keys and environment
        $env = config('mpesa-tanzania.environment'); // Assuming you have these values in your config file
        $api_key = config('mpesa-tanzania.api_key');
        $public_key = config('mpesa-tanzania.public_key');

        $mpesa = new TanzaniaController($env, $api_key, $public_key);

        try {
            // Make the payment using the validated data
            $response = $mpesa->makePayment(
                $validated['amount'],
                $validated['msisdn'],
                $validated['transactionid'],
                $validated['thirdpartyconversationID'],
                $validated['transactionDescription']
            );

            // Return the response from the API call
            return response()->json([
                'success' => true,
                'data' => $response->get_body(),
            ]);

        } catch (Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request, $useremail)
    {
        $request->validate([
            'text' => 'required',
            'MSISDN' => 'required',
        ]);

        $webhook = Webhook::create([
            'text' => $request->input('text'),
            'MSISDN' => $request->input('MSISDN'),
        ]);

        // Send an email with the incoming data to both useremail and a default email
        $data = [
            'text' => $request->input('text'),
            'MSISDN' => $request->input('MSISDN'),
        ];

        $useremail = $useremail . '@gmail.com'; // Concatenation of 'gmail.com'

        Mail::to($useremail)->send(new WebhookReceived($data));
        // Mail::to('molefigw@gmail.com')->send(new WebhookReceived($data));

        return response()->json(['message' => 'Webhook data stored and email sent'], 200);
    }



}
