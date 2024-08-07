<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    use HasFactory;

    protected $table = 'downloads';

    protected $fillable = [
        'artist',
        'title',
        'mobile',
        'file',
        'otp',
    ];

     /**
     * Download the file associated with the given OTP.
     *
     * @param string $otp
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    // public static function downloadFile($otp)
    // {
    //     $download = self::where('otp', $otp)->first();

    //     if ($download) {
    //         // Generate the file path (adjust the path as per your file storage configuration)
    //         $filePath = public_path('storage/' . $download->file);

    //         if (file_exists($filePath)) {
    //             // Download the file
    //             return response()->download($filePath);
    //         } else {
    //             // File not found
    //             return redirect()->back()->with('error', 'File not found.');
    //         }
    //     } else {
    //         // Invalid OTP
    //         return redirect()->back()->with('error', 'Invalid OTP.');
    //     }
    // }
}
