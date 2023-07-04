<?php

namespace DMarti\ExamplesSymfony5\Controller\Api;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerOrderController extends AbstractController
{
    #[Route('/api/customerOrders', methods: ['GET'])]
    public function list(): Response
    {
        // todo: get customerOrders from DB
        $customerOrders = [
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

        return $this->json($customerOrders);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['GET'])]
    public function show(int $orderId, LoggerInterface $logger): Response
    {
        // todo: get order from DB
        $order = [
            'id' => $orderId,
            'statusFulfillmentText' => 'Open',
            'fulfilledAt' => null,
            'createdAt' => '2032-06-30 09:24:21',
        ];

        /*
        To see API logs in Web Profiler, make the request then open:
        https://localhost:8000/_profiler
        Click on the token next to the request, and then select "Logs" from the (left) menu.
        */
        $logger->info('Getting order #{orderId}', [
            'orderId' => $orderId
        ]);

        return $this->json($order);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['PUT'])]
    public function update(int $orderId): Response
    {
        // todo: find order then update it in the DB

        return $this->json([]);
    }
}
