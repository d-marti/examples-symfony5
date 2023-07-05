<?php

namespace DMarti\ExamplesSymfony5\Controller\Api;

use DMarti\ExamplesSymfony5\Constant\CustomerOrderStatusFulfillment;
use DMarti\ExamplesSymfony5\Entity\CustomerOrder;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderProductRepository;
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
        private CustomerOrderRepository $customerOrderRepository,
        private CustomerOrderProductRepository $customerOrderProductRepository
    ) {
    }

    #[Route('/api/customerOrders', methods: ['GET'])]
    public function list(): Response
    {
        $customerOrders = $this->customerOrderRepository->findAll();

        return $this->json($customerOrders, Response::HTTP_OK, [], [
            'groups' => ['customerOrder:read']
        ]);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['GET'])]
    public function show(int $orderId, Request $request, LoggerInterface $logger): Response
    {
        $customerOrder = $this->customerOrderRepository->find($orderId);
        if (null === $customerOrder) {
            throw $this->createNotFoundException();
        }

        $notPacked = $request->query->get('notPacked', false);

        /*
        To see API logs in Web Profiler, make the request then open:
        https://localhost:8000/_profiler
        Click on the token next to the request, and then select "Logs" from the (left) menu.
        */
        $logger->info('Getting order #{orderId}', [
            'orderId' => $orderId
        ]);

        $order = $customerOrder->toArray();
        $order['products'] = [];

        $products = ($notPacked ? $customerOrder->getNotPackedProducts() : $customerOrder->getProducts());
        foreach ($products as $product) {
            $order['products'][] = $product->toArray();
        }

        return $this->json($order);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}/products/notPacked', methods: ['GET'])]
    public function showAllNotPackedProducts(int $orderId, Request $request): Response
    {
        $multiple = $request->query->get('multiple', false);
        $products = $this->customerOrderProductRepository->findAllNotPackedByOrderId($orderId, $multiple);
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = $product->toArray();
        }

        return $this->json($productsArray);
    }

    #[Route('/api/customerOrders/{orderId<\d+>}', methods: ['PUT'])]
    public function update(int $orderId, Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);
        if (null === $parameters || !isset($parameters['statusFulfillment'])) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $status = CustomerOrderStatusFulfillment::tryFrom($parameters['statusFulfillment']);
        if (null === $status) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        $customerOrder = $this->customerOrderRepository->find($orderId);
        if (null === $customerOrder) {
            throw $this->createNotFoundException();
        }

        $customerOrder->setStatusFulfillment($status);
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
