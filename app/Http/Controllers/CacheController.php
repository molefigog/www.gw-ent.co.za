<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    public function runOptimize(Request $request)
    {
        Artisan::call('optimize');

        return <<<HTML
            <script>
                alert("Cache updated successfully!");
                window.location.href = "/";
            </script>
        HTML;
    }
}
