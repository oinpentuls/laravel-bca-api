<?php

namespace Oinpentuls\BcaApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class Api extends Utility
{
    use UriTrait;

    public function getToken()
    {
        try {
            $client = new Client();
            $credentials = base64_encode(config('bca.client_id') . ':' . config('bca.client_secret'));

            $response = $client->request('POST', config('bca.host') . $this->tokenUri(), [
                'headers' => [
                    'Authorization' => 'Basic ' . $credentials,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $result['access_token'];
        } catch (RequestException $e) {
            return response()->json([
                'status' => $e->getResponse()->getStatusCode(),
                'message' => $e->getMessage(),
            ]);
        } catch (ConnectException $e) {
            return [
                'status' => $e->getHandlerContext()['http_code'],
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAccess(string $method, string $uri, array $body = null): array
    {
        if (empty($body)) {
            $hash = hash("sha256", "");
        } else {
            $encoderData = json_encode($body, JSON_UNESCAPED_SLASHES);
            $hash = strtolower(hash("sha256", $encoderData));
        }
        $token = $this->getToken();
        $timeStamp = $this->getTime();

        $stringToSign = "{$method}:{$uri}:{$token}:{$hash}:{$timeStamp}";

        return [
            'signature' => hash_hmac('sha256', $stringToSign, config('bca.api_secret')),
            'timestamp' => $timeStamp,
            'token' => $token,
            'uri' => $uri
        ];
    }

    public function sendRequest(string $method, string $uri, array $body = null, array $headers = []): array
    {
        $access = $this->getAccess($method, trim($uri), $body);

        $defaultHeader = [
            'X-BCA-Signature' => $access['signature'],
            'Authorization' => "Bearer " . $access['token'],
            'X-BCA-Key' => config('bca.api_key'),
            'X-BCA-Timestamp' => $access['timestamp'],
            'Content-Type' => 'application/json',
        ];

        $defaultHeader += $headers;

        try {
            $client = new Client();

            $result = $client->request($method, config('bca.host') . $access['uri'], [
                'headers' => $defaultHeader,
                'json' => $body
            ]);

            return [
                'status' => $result->getStatusCode(),
                'message' => json_decode($result->getBody()->getContents(), true)
            ];
        } catch (RequestException $e) {
            return [
                'status' => $e->getResponse()->getStatusCode(),
                'message' => $e->getMessage()
            ];
        } catch (ConnectException $e) {
            return [
                'status' => $e->getRequest(),
                'message' => $e->getMessage()
            ];
        }
    }
}
