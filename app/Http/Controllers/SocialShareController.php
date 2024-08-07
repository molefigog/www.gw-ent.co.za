<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Music;

class SocialShareController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $baseUrl = url(); // Get the base URL dynamically

        $shareButtons = \Share::page($baseUrl, 'share')
            ->facebook()
            ->twitter()
            ->linkedin()
            ->telegram()
            ->whatsapp()
            ->reddit();

        $posts = Post::get();
        $music = Music::get();

        return view('socialshare', compact('shareButtons', 'posts', 'music'));
    }
}
