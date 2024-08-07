<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoSms;

class MoSmsController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'To' => 'required|string',
            'ID' => 'required|integer',
            'Message' => 'required|string',
            'Msisdn' => 'required|string',
            'Received' => 'required|integer',
            'UserReference' => 'required|string|max:100',
        ]);

        // Define default values for optional fields
        $defaultEventId = null;
        $defaultMsisdn = " ";
        $defaultStatus = " ";
        $defaultReceived = null;
        $defaultUserReference = "";

        // Fill in optional fields if they are missing
        $eventId = $validatedData['EventId'] ?? $defaultEventId;
        $msisdn = $validatedData['Msisdn'] ?? $defaultMsisdn;
        $status = $validatedData['Status'] ?? $defaultStatus;
        $receivedDateTime = $validatedData['Received'] ?? $defaultReceived;
        $userReference = $validatedData['UserReference'] ?? $defaultUserReference;

        // Convert UNIX timestamp to DateTime object
        if (!is_null($receivedDateTime)) {
            $receivedDateTime = \Carbon\Carbon::createFromTimestamp($receivedDateTime);
        }

        // Store the validated data in the database
        MoSms::create([
            'To' => $validatedData['To'],
            'ID' => $validatedData['ID'],
            'Message' => $validatedData['Message'],
            'Msisdn' => $msisdn,
            'Received' => $receivedDateTime,
            'UserReference' => $userReference,
            'EventId' => $eventId,
            'Status' => $status,
        ]);

        return response()->json(['message' => 'SMS message stored successfully']);
    }
}
