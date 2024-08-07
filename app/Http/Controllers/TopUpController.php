<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\models\Items;
use App\Models\Music;
use App\Models\Beat;
use Illuminate\Support\Facades\Auth;
use App\Models\WebhookData;
use App\Models\Manualpay;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class TopUpController extends Controller
{
    public function showTopUpForm()
    {
        $Paypal = config('paypal.paypal_url');
        $PAYPAL_ID = config('paypal.paypal_id');
        $currency = config('paypal.paypal_currency');
        $upload_fee = '100';
        // $userId = Auth::user()->id;
        $user = auth()->user();
        $songs = $user->music;
        return view('top-up', compact('songs', 'Paypal', 'PAYPAL_ID', 'currency', 'upload_fee'));
    }

    public function processTopUp(Request $request)
    {
        $transactionId = $request->input('transaction_id');
        $webhookData = WebhookData::where('transact_id', $transactionId)->where('used', false)->first();

        if ($webhookData) {
            $webhookData->markAsUsed();
            $user = Auth::user();
            $user->balance += $webhookData->received_amount;
            $user->save();

            return redirect()->route('top-up')->with('success', 'Balance updated successfully.');
        } else {
            return redirect()->route('top-up')->with('error', 'Transaction ID not found or already used.');
        }
    }
    public function processOrder(Request $request)
    {
        $otp = $request->input('otp');
        $manualPay = Manualpay::where('otp', $otp)->where('used', false)->first();

        if ($manualPay) {
            $musicId = $request->input('music_id');
            $music = Music::find($musicId);
            $downloadUrl = route('music.download', ['music' => $musicId]);

            if (!$music) {
                Log::error('Music track not found for ID: ' . $musicId);
                return redirect()->back()->with('error', 'Music track not found.');
            }
            if ($manualPay->received_amount >= $music->amount) {
                $manualPay->received_amount -= $music->amount;
                $manualPay->save();
                return redirect($downloadUrl);
            } elseif ($manualPay->received_amount == $music->amount) {
                $manualPay->markAsUsed();
                return redirect($downloadUrl);
            } else {
                return redirect()->back()->with('error', 'Insufficient funds. Please try again with the correct amount.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid OTP or order already used.');
        }
    }

    public function beatOrder(Request $request)
    {
        $otp = $request->input('otp');
        $manualPay = Manualpay::where('otp', $otp)->where('used', false)->first();

        if ($manualPay) {
            $beatId = $request->input('beat_id');
            $beat = Beat::find($beatId);
            $downloadUrl = route('beat.download', ['beat' => $beatId]);

            if (!$beat) {
                Log::error('Beat track not found for ID: ' . $beatId);
                return redirect()->back()->with('error', 'Beat track not found.');
            }
            if ($manualPay->received_amount >= $beat->amount) {
                $manualPay->received_amount -= $beat->amount;
                $manualPay->save();
                return redirect($downloadUrl);
            } elseif ($manualPay->received_amount == $beat->amount) {
                $manualPay->markAsUsed();
                return redirect($downloadUrl);
            } else {
                return redirect()->back()->with('error', 'Insufficient funds. Please try again with the correct amount.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid OTP or order already used.');
        }
    }

    public function downloadSong($musicId)
    {
        Log::info('Music ID: ' . $musicId);
        $music = Music::find($musicId);
        if (!$music) {
            Log::error('Music track not found for ID: ' . $musicId);
            return redirect()->back()->with('error', 'Music track not found.');
        }
        $musicFilePath = $music->file;
        $music->md++;
        $music->downloads++;
        $music->save();
        if (!Storage::exists($musicFilePath)) {
            Log::error('Music file not found in storage: ' . $musicFilePath);
            return redirect()->back()->with('error', 'Music file not found.');
        }
        $response = Storage::download($musicFilePath, $music->artist . '-' . $music->title . '.mp3');
        Log::info('Download Response: ' . $response);
        return $response;
    }

    public function downloadbeat($beatId)
    {
        Log::info('Music ID: ' . $beatId);
        $beat = Music::find($beatId);
        if (!$beat) {
            Log::error('Music track not found for ID: ' . $beatId);
            return redirect()->back()->with('error', 'Music track not found.');
        }
        $beatFilePath = $beat->file;
        if ($beat->used !== 1) {
            $beat->used += 1;
            $beat->save();
        }
        if (!Storage::exists($beatFilePath)) {
            Log::error('Music file not found in storage: ' . $beatFilePath);
            return redirect()->back()->with('error', 'Music file not found.');
        }
        $response = Storage::download($beatFilePath, $beat->artist . '-' . $beat->title . '.mp3');
        Log::info('Download Response: ' . $response);
        return $response;
    }
    public function checkOtp(Request $request)
    {
        $otp = $request->input('otp');
        $manualPay = Manualpay::where('otp', $otp)->where('used', false)->first();

        if ($manualPay) {
            return response()->json(['success' => true, 'receivedAmount' => $manualPay->received_amount, 'fromNumber' => $manualPay->from_number]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function index()
    {
        $user = auth()->user();
        $songs = $user->music;
        return view('sales', compact('songs'));
    }
}
