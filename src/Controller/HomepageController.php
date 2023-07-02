<?php

namespace DMarti\ExamplesSymfony5\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
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

    #[Route('/docs', name: 'app_homepage_docs')]
    public function docs(string $projectDir): Response
    {
        return $this->render('homepage/docs.html.twig', [
            'readmeMd' => file_get_contents($projectDir . DIRECTORY_SEPARATOR . 'README.md')
        ]);
    }

    #[Route('/about', name: 'app_homepage_about')]
    public function about(): Response
    {
        return $this->render('homepage/about.html.twig');
    }

    #[Route('/license', name: 'app_homepage_license')]
    public function license(string $projectDir): Response
    {
        return $this->render('homepage/license.html.twig', [
            'license' => file_get_contents($projectDir . DIRECTORY_SEPARATOR . 'LICENSE')
        ]);
    }
}
