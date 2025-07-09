<?php

namespace App\Services;

use GuzzleHttp\Client;

class OAuthService
{
    private array $response = [
        'status' => null,
        'message' => null,
        'data' => null,
        'code' => 200,
    ];

    public function execute()
    {
        try {
            $clientId = config('services.bunnings.client_id');
            $clientSecret = config('services.bunnings.client_secret');
            $authUrl = config('services.bunnings.auth_url');

            $client = new Client();

            $response = $client->post($authUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'itm:details'
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            $this->response['status'] = true;
            $this->response['message'] = 'Token fetched successfully';
            $this->response['data'] = [
                'access_token' => $data['access_token'] ?? null,
                'expires_in' => $data['expires_in'] ?? null,
                'token_type' => $data['token_type'] ?? null,
                'scope' => $data['scope'] ?? null,
            ];

            return $this->response;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $errorBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : $e->getMessage();
            $this->response['status'] = false;
            $this->response['message'] = 'Request failed: ' . $errorBody;
            $this->response['code'] = $e->getCode() ?: 500;
            return $this->response;
        } catch (\Exception $e) {
            $this->response['status'] = false;
            $this->response['message'] = $e->getMessage();
            $this->response['code'] = $e->getCode() ?: 500;
            return $this->response;
        }
    }
}
