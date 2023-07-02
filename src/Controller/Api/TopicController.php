<?php

namespace DMarti\ExamplesSymfony5\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    #[Route('/api/topics/{slug}/vote/{direction<up|down>}', methods: ['PUT'])]
    public function vote(string $slug, string $direction): Response
    {
        // todo: use slug to query the DB

        $votes = ($direction === 'up' ? rand(1, 100) : rand(-50, 2));

        return $this->json(['votes' => $votes]);
    }
}
