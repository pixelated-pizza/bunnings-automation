<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Order;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function fetchBunningsOrders()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post(config('services.neto.url'), [
            'headers' => [
                'NETOAPI_KEY' => config('services.neto.key'),
                'NETOAPI_ACTION' => 'GetOrder',
                'Accept' => 'application/json',
            ],
            'json' => [
                'Filter' => [
                    'OrderStatus' => ['Pick', 'Pack'],
                    'OrderSource' => ['Bunnings'],
                ],
                'OutputSelector' => ['OrderID'],
            ],
        ]);

        $body = json_decode($response->getBody(), true);

        if (empty($body['Order'])) {
            return response()->json(['message' => 'No new orders found.']);
        }

        $orderIDs = collect($body['Order'])->pluck('OrderID')->all();

        $detailsResponse = $client->post(config('services.neto.url'), [
            'headers' => [
                'NETOAPI_KEY' => config('services.neto.key'),
                'NETOAPI_ACTION' => 'GetOrder',
                'Accept' => 'application/json',
            ],
            'json' => [
                'Filter' => [
                    'OrderID' => $orderIDs,
                    'SalesChannel' => 'Bunnings',
                    'OutputSelector' => [
                        'OrderID',
                        'SalesChannel',
                        'DatePlaced',
                        'OrderStatus',
                        'ShipAddress',
                        'OrderLine',
                    ],
                ],
            ],
        ]);

        $detailsBody = json_decode($detailsResponse->getBody(), true);

        // dd($detailsBody);


        if (empty($detailsBody['Order'])) {
            return response()->json(['message' => 'No details returned! Check OutputSelector or OrderIDs.']);
        }

        foreach ($detailsBody['Order'] as $netoOrder) {
            Order::updateOrCreate(
                ['order_id' => $netoOrder['OrderID']],
                [
                    'status' => $netoOrder['OrderStatus'] ?? 'Unknown',
                    'sales_channel' => $netoOrder['SalesChannel'] ?? 'Unknown',
                    'date_placed' => $netoOrder['DatePlaced'] ?? null,
                    'ship_address' => implode(', ', array_filter([
                        trim(($netoOrder['ShipFirstName'] ?? '') . ' ' . ($netoOrder['ShipLastName'] ?? '')),
                        $netoOrder['ShipStreetLine1'] ?? '',
                        $netoOrder['ShipStreetLine2'] ?? '',
                        $netoOrder['ShipCity'] ?? '',
                        $netoOrder['ShipState'] ?? '',
                        $netoOrder['ShipPostCode'] ?? '',
                        $netoOrder['ShipCountry'] ?? '',
                        $netoOrder['ShipPhone'] ?? '',
                    ])),
                    'order_lines' => $netoOrder['OrderLine'] ?? [],
                ]
            );
        }



        return response()->json([
            'message' => 'Imported ' . count($detailsBody['Order']) . ' Bunnings orders.'
        ]);
    }
}
