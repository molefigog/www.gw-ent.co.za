<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bookings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File; // Add this import
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class BookingController extends Controller
{
    public function showArtistBookings($artistName)
    {
        $decodedArtistName = str_replace('_', ' ', $artistName);
        $artist = User::where('name', $decodedArtistName)->firstOrFail();

        // Fetch the first booking for the artist
        $booking = Bookings::where('user_id', $artist->id)->first();

        return view('bookings.artist', [
            'artist' => $artist,
            'booking' => $booking,
        ]);
    }
    // public function generatePdf($artistId)
    // {
    //     // Fetch artist and booking data
    //     $artist = User::findOrFail($artistId);
    //     $booking = Bookings::where('user_id', $artistId)->firstOrFail();

    //     // Generate HTML content for the PDF
    //     $html = view('pdf.booking', [
    //         'artist' => $artist,
    //         'booking' => $booking,
    //     ])->render();

    //     // Create PDF
    //     $pdf = App::make('dompdf.wrapper');
    //     $pdf->loadHTML($html);

    //     // Stream the PDF
    //     return $pdf->stream($artist->name . '-booking.pdf');
    // }
    public function generatePdf($artistId)
    {
        // Fetch artist and booking data
        $artist = User::findOrFail($artistId);
        $booking = Bookings::where('user_id', $artistId)->firstOrFail();

        // Generate HTML content for the PDF
        $html = view('pdf.booking', [
            'artist' => $artist,
            'booking' => $booking,
        ])->render();

        // Create and configure PDF
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        // Optionally set paper size and orientation
        $pdf->setPaper('a4', 'portrait');

        // Stream the PDF
        return $pdf->stream($artist->name . '-booking.pdf');
    }



}
