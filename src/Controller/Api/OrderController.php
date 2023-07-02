<?php

namespace DMarti\ExamplesSymfony5\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/api/orders', methods: ['GET'])]
    public function list(): Response
    {
        // todo: get orders from DB
        $orders = [
            [
                'id' => 1234,
                'statusFulfillmentText' => 'Open',
                'fulfilledAt' => null,
                'createdAt' => '2032-06-30 09:24:21',
            ],
            [
                'id' => 1245,
                'statusFulfillmentText' => 'Cancelled',
                'fulfilledAt' => null,
                'createdAt' => '2032-06-30 09:35:14',
            ],
            [
                'id' => 1256,
                'statusFulfillmentText' => 'Delivered',
                'fulfilledAt' => null,
                'createdAt' => '2032-06-30 09:47:03',
            ],
        ];

        return $this->json($orders);
    }

    #[Route('/api/orders/{orderId<\d+>}', methods: ['GET'])]
    public function show(int $orderId): Response
    {
        // todo: get order from DB
        $order = [
            'id' => $orderId,
            'statusFulfillmentText' => 'Open',
            'fulfilledAt' => null,
            'createdAt' => '2032-06-30 09:24:21',
        ];

        return $this->json($order);
    }

    #[Route('/api/orders/{orderId<\d+>}', methods: ['PUT'])]
    public function update(int $orderId): Response
    {
        // todo: find order then update it in the DB

        return $this->json([]);
    }
}
