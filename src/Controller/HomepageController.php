<?php

namespace DMarti\ExamplesSymfony5\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        $topics = [
            [
                'title' => 'Basic setup',
                'slug' => 'basic-setup',
                'updatedAt' => (new DateTimeImmutable('2023-06-27')),
                'votes' => rand(1, 100),
            ],
            [
                'title' => 'Routing',
                'slug' => 'routing',
                'updatedAt' => (new DateTimeImmutable('2023-06-28')),
                'votes' => rand(1, 50),
            ],
            [
                'title' => 'API setup',
                'slug' => 'api-setup',
                'updatedAt' => (new DateTimeImmutable('2023-06-29')),
                'votes' => rand(-50, 1),
            ],
            [
                'title' => 'Templating',
                'slug' => 'templating',
                'updatedAt' => null,
                'votes' => 0,
            ],
        ];

        return $this->render('homepage/homepage.html.twig', [
            'topics' => $topics
        ]);
    }
}
