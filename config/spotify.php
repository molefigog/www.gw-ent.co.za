<?php

// config/spotify.php

return [
    'client' => env('SPOTIFY_CLIENT_ID', ''),
    'secret' => env('SPOTIFY_CLIENT_SECRET', ''),
    'redirect' => env('SPOTIFY_REDIRECT_URI', ''),
];
