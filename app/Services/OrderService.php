<?php

namespace App\Services;

use App\Models\Order;
use GuzzleHttp\Client;


class OrderService
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
            
            $this->response['status'] = true;
            $this->response['message'] = '';
            $this->response['data'] = [
                
            ];
            return $this->response;
        } catch (\Exception $e) {
            $this->response['status'] = false;
            $this->response['message'] = $e->getMessage();
            $this->response['code'] = (int)$e->getCode() ?: 500;
            return $this->response;
        }
    }
}
