<?php

namespace DMarti\ExamplesSymfony5\Controller;

use DMarti\ExamplesSymfony5\Service\TopicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(TopicService $topicService): Response
    {
        $topics = $topicService->getTopics();

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
