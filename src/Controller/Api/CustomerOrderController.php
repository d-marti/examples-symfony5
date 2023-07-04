<?php

namespace DMarti\ExamplesSymfony5\Controller\Api;

use DMarti\ExamplesSymfony5\Entity\CustomerOrder;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerOrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CustomerOrderRepository $customerOrderRepository
    ) {
    }

    #[Route('/api/customerOrders', methods: ['GET'])]
    public function list(): Response
    {
        $customerOrders = $this->customerOrderRepository->findAll();

        $orders = [];
        foreach ($customerOrders as $order) {
            $orders[] = $order->toArray(); // there are nicer ways of doing this, but that's in a future example
        }

        return $this->json($orders);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['GET'])]
    public function show(int $orderId, LoggerInterface $logger): Response
    {
        $customerOrder = $this->customerOrderRepository->find($orderId);
        if (null === $customerOrder) {
            return $this->createNotFoundException();
        }

        /*
        To see API logs in Web Profiler, make the request then open:
        https://localhost:8000/_profiler
        Click on the token next to the request, and then select "Logs" from the (left) menu.
        */
        $logger->info('Getting order #{orderId}', [
            'orderId' => $orderId
        ]);

        return $this->json(
            $customerOrder->toArray() // there are nicer ways of doing this, but that's in a future example
            // also, note that with Twig you don't need to convert an object to an array, since Twig is smart
        );
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['PUT'])]
    public function update(int $orderId, Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);
        if (
            null === $parameters ||
            !isset($parameters['statusFulfillment']) ||
            !preg_match('/^[1-3]{1}$/', $parameters['statusFulfillment'])
        ) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $customerOrder = $this->customerOrderRepository->find($orderId);
        if (null === $customerOrder) {
            return $this->createNotFoundException();
        }

        $customerOrder->setStatusFulfillment((int) $parameters['statusFulfillment']);
        // $this->entityManager->persist($customerOrder); // not necessary, since we queried for the object with "find"
        $this->entityManager->flush();

        return new Response('', Response::HTTP_OK);
    }

    #[Route('/api/customerOrders', methods: ['POST'])]
    public function create(): Response
    {
        $customerOrder = new CustomerOrder();
        $this->entityManager->persist($customerOrder);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED);
    }
}
