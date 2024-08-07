<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Music;
use App\Models\Payment;
use App\Models\User;
use App\Models\Items;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Mail\AlertMail;
use App\Mail\PurchaseConfirmationMail;
use Illuminate\Support\Facades\Mail;

class PaypalController extends Controller
{
    public function index()
    {
        // Retrieve the currently logged-in user
        $user = auth()->user();

        // Retrieve the payment history with associated music titles for the logged-in user
        $payments = Payment::with('music')->where('user_id', $user->id)->get();

        // Return the payment history view with the payments data
        return view('paypal.transaction', compact('payments'));
    }

    public function handleSuccess(Request $request)
    {
        try {
            // If transaction data is available in the URL
            if ($request->has(['item_number', 'tx', 'amt', 'cc', 'st', 'custom'])) {
                // Get transaction information from the request
                $item_number = $request->input('item_number');
                $txn_id = $request->input('tx');
                $payment_gross = $request->input('amt');
                $currency_code = $request->input('cc');
                $payment_status = $request->input('st');
                $custom = $request->input('custom');
                // Use a different variable name

                // Get product info from the database
                $productRow = Music::find($item_number);
                $productUser = User::find($custom);

                $music = Music::find($item_number);

                $uploaderId = DB::table('music_user')
                    ->where('music_id', $music->id)
                    ->value('user_id');

                // Retrieve the uploader
                $uploader = User::find($uploaderId);

                // Notify the uploader about the purchase
                if ($uploader) {
                    $buyer = Auth::user();
                    $uploader->notify(new AlertMail($music, $productUser));
                    Mail::to($buyer->email)->send(new PurchaseConfirmationMail($buyer, $payment_gross, $payment_status, $txn_id, $productRow));
                } else {
                    throw new \Exception("Uploader not found with ID: $uploaderId");
                }
                // Log transaction and product information
                Log::info("Transaction Info: " . print_r($request->all(), true));
                Log::info("Product Info: " . print_r($productRow, true));

                $Items = new Items([
                    'user_id' => $custom,
                    'music_id' => $productRow->id,
                    'uploader_id' => $uploaderId,
                    'artist' => $productRow->artist,
                    'title' => $productRow->title,
                    'image' => $productRow->image,
                    'file' => $productRow->file,
                    'duration' => $productRow->duration,
                    'size' => $productRow->size,
                ]);

                $productRow->md++;
                $productRow->downloads++;
                $productRow->save();
                $Items->save();

                $prevPaymentRow = Payment::where('txn_id', $txn_id)->first();

                if ($prevPaymentRow) {
                    $payment_id = $prevPaymentRow->payment_id;
                    $payment_gross = $prevPaymentRow->payment_gross;
                    $payment_status = $prevPaymentRow->payment_status;

                    // Log existing payment information
                    Log::info("Existing Payment ID: $payment_id");
                    Log::info("Existing Payment Gross: $payment_gross");
                    Log::info("Existing Payment Status: $payment_status");
                } else {
                    // Insert transaction data into the database
                    $payment_id = DB::table('payments')->insertGetId([
                        'user_id' => $custom,
                        'music_id' => $productRow->id,
                        'item_number' => $item_number,
                        'txn_id' => $txn_id,
                        'payment_gross' => $payment_gross,
                        'currency_code' => $currency_code,
                        'payment_status' => $payment_status,
                    ]);

                    // Log newly inserted payment information
                    Log::info("Newly Inserted Payment ID: $payment_id");
                }
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error("Exception: " . $e->getMessage());
        }

        return view('paypal.success', compact('payment_id', 'txn_id', 'payment_gross', 'payment_status', 'productRow', 'productUser'));
    }


    public function handleIPN(Request $request)
    {
        // Get raw POST data from the request
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();

        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        // Log processed POST data
        file_put_contents('ipn_log.txt', date('Y-m-d H:i:s') . " - Processed POST Data: " . print_r($myPost, true) . "\n", FILE_APPEND);

        // Construct the request for validation
        $req = 'cmd=_notify-validate';

        foreach ($myPost as $key => $value) {
            // Use addslashes to escape special characters
            $value = urlencode(addslashes($value));
            $req .= "&$key=$value";
        }

        // Log the constructed request
        file_put_contents('ipn_log.txt', date('Y-m-d H:i:s') . " - IPN Request: " . $req . "\n", FILE_APPEND);

        // Post IPN data back to PayPal to validate
        $paypalURL = config('paypal.paypal_ipn'); // Make sure to define 'paypal.url' in your config file
        $ch = curl_init($paypalURL);

        if ($ch == FALSE) {
            // Log cURL initialization error
            file_put_contents('ipn_log.txt', date('Y-m-d H:i:s') . " - cURL Initialization Error\n", FILE_APPEND);
            return FALSE;
        }

        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));


        $response = curl_exec($ch);
        $httpResponse = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Execute cURL
        $res = curl_exec($ch);

        // Log cURL response
        Log::info('cURL Response Code: ' . $httpResponse);
        Log::info('cURL Response Body: ' . $response);
        // Log cURL response

        // Inspect IPN validation result and act accordingly
        $tokens = explode("\r\n\r\n", trim($res));
        $res = trim(end($tokens));

        if ($httpResponse == 200 && trim($response) == 'VERIFIED') {
            // Retrieve transaction info from PayPal
            $item_number = $myPost['item_number'];
            $txn_id = $myPost['txn_id'];
            $payment_gross = $myPost['mc_gross'];
            $currency_code = $myPost['mc_currency'];
            $payment_status = $myPost['payment_status'];

            // Log transaction info
            Log::info('Transaction Info: ' . print_r($myPost, true));

            // Check if transaction data exists with the same TXN ID
            $prevPayment = DB::table('payments')->where('txn_id', $txn_id)->first();

            if ($prevPayment) {
                // Log duplicate transaction
                Log::info('Duplicate Transaction. Exiting.');
                exit();
            } else {
                // Insert transaction data into the database
                DB::table('payments')->insert([
                    'item_number' => $item_number,
                    'txn_id' => $txn_id,
                    'payment_gross' => $payment_gross,
                    'currency_code' => $currency_code,
                    'payment_status' => $payment_status,
                ]);

                // Log successful insertion
                Log::info('Transaction Inserted Successfully.');
            }
        } else {
            // Log IPN verification failure
            Log::info('IPN Verification Failed');
            Log::info('IPN Verification Failed');
        }
    }


    public function returnUrl(Request $request)
    {
        try {
            // If transaction data is available in the URL
            if ($request->has(['item_number', 'tx', 'amt', 'cc', 'st', 'custom'])) {
                // Get transaction information from the request
                $item_number = $request->input('item_number');
                $txn_id = $request->input('tx');
                $payment_gross = $request->input('amt');
                $currency_code = $request->input('cc');
                $payment_status = $request->input('st');
                $custom = $request->input('custom');

                // Get product info from the database
                $productRow = 'registration fee';
                $productUser = User::find($custom);
                $music = Music::find($item_number);

                $user = Auth::user();

                // Log transaction and product information
                Log::info("Transaction Info: " . print_r($request->all(), true));
                Log::info("Product Info: " . print_r($productRow, true));

                $user->balance += $payment_gross;
                $user->upload_status += 1; // Fix: Use += instead of =+
                $user->save();

                $prevPaymentRow = Payment::where('txn_id', $txn_id)->first();

                if ($prevPaymentRow) {
                    $payment_id = $prevPaymentRow->payment_id;
                    $payment_gross = $prevPaymentRow->payment_gross;
                    $payment_status = $prevPaymentRow->payment_status;

                    // Log existing payment information
                    Log::info("Existing Payment ID: $payment_id");
                    Log::info("Existing Payment Gross: $payment_gross");
                    Log::info("Existing Payment Status: $payment_status");
                } else {
                    // Insert transaction data into the database
                    $payment_id = DB::table('payments')->insertGetId([
                        'user_id' => $custom,
                        'music_id' => 0,
                        'item_number' => $item_number,
                        'txn_id' => $txn_id,
                        'payment_gross' => $payment_gross,
                        'currency_code' => $currency_code,
                        'payment_status' => $payment_status,
                    ]);

                    // Log newly inserted payment information
                    Log::info("Newly Inserted Payment ID: $payment_id");
                }
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur
            Log::error("Exception: " . $e->getMessage());
        }

        return view('paypal.success2', compact('payment_id', 'txn_id', 'payment_gross', 'payment_status', 'productRow', 'productUser'));
    }
}
