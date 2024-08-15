<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;
use SpotifyWebAPI\Session;
use Illuminate\Support\Facades\Log;
class SpotifyController extends Controller
{
    protected $api;
    protected $session;

    public function __construct()
{
    $clientId = config('spotify.client');
    $clientSecret = config('spotify.secret');
    $redirectUri = config('spotify.redirect');

    if (!$clientId || !$clientSecret || !$redirectUri) {
        // Log an error or throw an exception if any of the environment variables are not set
        throw new \Exception('Spotify API credentials are not set in the environment variables.');
    }

    $this->session = new Session($clientId, $clientSecret, $redirectUri);
    $this->api = new SpotifyWebAPI();
}


    public function redirectToSpotify()
    {
        $authUrl = $this->session->getAuthorizeUrl([
            'scope' => [
                'user-library-read',
                'user-read-private',
            ],
        ]);

        return redirect($authUrl);
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $this->session->requestAccessToken($code);
        $accessToken = $this->session->getAccessToken();

        Log::info('Access Token: ' . $accessToken); // Log the access token for debugging

        if (!$accessToken) {
            throw new \Exception('Failed to retrieve access token from Spotify.');
        }

        session(['spotify_access_token' => $accessToken]);

        return redirect()->route('songs.index');
    }

    public function getSongs()
{
    $accessToken = session('spotify_access_token');
    Log::info('Retrieved Access Token: ' . $accessToken); // Log the access token for debugging

    if (!$accessToken) {
        return redirect()->route('spotify.redirect')->withErrors('Access token not found. Please authenticate with Spotify.');
    }

    $this->api->setAccessToken($accessToken);

    try {
        $albums = $this->api->getArtistAlbums('6dxllKWGLX72zxSJ3MhgK4'); // Use $this->api instead of $api
        return view('songs.index', ['albums' => $albums->items]); // Adjust view variable to match albums
    } catch (SpotifyWebAPIException $e) {
        return back()->withErrors('Unable to fetch albums from Spotify.');
    }
}


}
