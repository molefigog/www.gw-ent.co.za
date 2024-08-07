<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use phpseclib3\Crypt\PublicKeyLoader;

class TzController extends Controller
{
    const VERSION = '0.0.9';
    private $options;
    private $client;
    const BASE_DOMAIN = "https://openapi.m-pesa.com";
    private $sessionToken;

    const TRANSACT_TYPE = [
        'c2b' => [
            'name' => 'Consumer 2 Business',
            'url' => "c2bPayment/singleStage/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
    ];
    public function __construct(array $options, $client = null)
    {
        if (!key_exists('api_key', $options)) throw new  InvalidArgumentException("api_key is required");
        if (!key_exists('public_key', $options)) throw new  InvalidArgumentException("public_key is required");

        $options['client_options'] = $options['client_options'] ?? [];
        $options['persistent_session'] = $options['persistent_session'] ?? false;

        $options['service_provider_code'] = $options['service_provider_code'] ?? null;
        $options['country'] = $options['country'] ?? null;
        $options['currency'] = $options['currency'] ?? null;

        $this->options = $options;
        $this->client = $this->makeClient($options, $client);
    }
    public function setPublicKey(string $publicKey)
    {
        $this->options['public_key'] = $publicKey;
    }

    public function setApiKey(string $apiKey)
    {
        $this->options['api_key'] = $apiKey;
    }
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }
    private function makeClient(array $options, $client = null): Client
    {
        $apiUrl = "";
        if (array_key_exists("env", $options)) {
            $apiUrl = ($options['env'] === "sandbox") ? self::BASE_DOMAIN . "/sandbox" : self::BASE_DOMAIN . "/openapi";
        } else {
            $apiUrl =  self::BASE_DOMAIN . "/sandbox";
        }
        $apiUrl .= "/ipg/v2/vodacomLES/";

        return ($client instanceof Client)
            ? $client
            : new Client(array_merge([
                'http_errors' => false,
                'base_uri' => $apiUrl,
                'headers' => [
                    'Accept' => 'application/json',
                    'Origin' => 'http://102.219.85.46'
                ]
            ], $options['client_options']));
    }
    public function encryptKey($key): string
    {
        $pKey = PublicKeyLoader::load($this->options['public_key']);
        openssl_public_encrypt($key, $encrypted, $pKey);
        return base64_encode($encrypted);
    }
    public function getSession()
    {
        $response = $this->client->get(
            'getSession/',
            ['headers' => ['Authorization' => "Bearer {$this->encryptKey($this->options['api_key'])}"]]
        );

        return json_decode($response->getBody(), true);
    }
    private function getSessionToken($session = null)
    {
        if ($session) return $session;
        if ($this->options['persistent_session'] == true && $this->sessionToken) {
            return $this->sessionToken;
        }
        $resSession = $this->getSession();
        error_log('Response session: ' . print_r($resSession, true));
        if (isset($resSession['output_ResponseCode'])) {
            if ($resSession['output_ResponseCode'] == 'INS-0') {
                if ($this->options['persistent_session'] == true) {
                    $this->sessionToken = $resSession['output_SessionID'];
                }
                return $resSession['output_SessionID'];
            } else {
                $responseCode = $resSession['output_ResponseCode'];
                $responseDesc = $resSession['output_ResponseDesc'] ?? "Error Processing Request";
                throw new Exception($responseDesc, $responseCode);
            }
        } else {
            error_log('Unexpected response structure: ' . json_encode($resSession));
            if (isset($resSession['output_error']) && $resSession['output_error'] === "Origin not allowed") {
                throw new Exception('API request blocked due to origin not being allowed. Please check CORS settings or API configuration.');
            } else if (isset($resSession['error']) && is_array($resSession['error'])) {
                // Example of handling an error structure
                $errorDetails = $resSession['error'];
                $responseCode = $errorDetails['code'] ?? 0;
                $responseDesc = $errorDetails['message'] ?? "Unknown error occurred";
                throw new Exception($responseDesc, $responseCode);
            } else {
                // General fallback exception for unknown response structures
                throw new Exception('Unexpected response structure: ' . json_encode($resSession));
            }
        }
    }
    private function makeRequestData($data)
    {
        $data['input_ServiceProviderCode'] = $data['input_ServiceProviderCode'] ?? $this->options['service_provider_code'];
        $data['input_Country'] = $data['input_Country'] ?? $this->options['country'];
        $data['input_Currency'] = $data['input_Currency'] ?? $this->options['currency'];

        return $data;
    }
    public function query($data, $session = null)
    {
        $session = $this->getSessionToken($session);
        $transData = $this->makeRequestData($data);
        $response = $this->client->get(self::TRANSACT_TYPE['query']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$this->encryptKey($session)}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    public function c2b($data, $session = null)
    {
        $sessionToken = $this->getSessionToken($session);
        $transData = $this->makeRequestData($data);
        $token = $this->encryptKey($sessionToken);
        $response = $this->client->post(self::TRANSACT_TYPE['c2b']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        $responseData = json_decode($response->getBody(), true);

    // Log the response
    \Log::info('Response from c2b API:', $responseData);

    return $responseData;
    }
    public function charge()
    {
        $data = [
            'input_Amount' => 5000, // Amount to be charged
            'input_Country' => 'LES',
            'input_Currency' => 'LSL',
            'input_CustomerMSISDN' => '59073443', // replace with your phone number
            'input_ServiceProviderCode' => '51909', // replace with your service provider code given by M-Pesa
            'input_ThirdPartyConversationID' => 'rasderekf', // unique
            'input_TransactionReference' => 'asdodfdferre', // unique
            'input_PurchasedItemsDesc' => 'Test Item'
        ];

        try {
            $response = $this->c2b($data);
            return response()->json($response, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}
