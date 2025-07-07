<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RequestResponse;
use App\Services\OrderService;

class BunningsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

   public function fetch_orders(OrderService $service) {
        $result = $service->execute();
        if ($result['status']) {
            return RequestResponse::setData($result['data'])->success();
        }
        switch ($result['code']) {
            case 401:
                return RequestResponse::setMessage($result['message'])->unauthorized();
            default:
                return RequestResponse::setMessage($result['message'])->serverError();
        }
    }
}
