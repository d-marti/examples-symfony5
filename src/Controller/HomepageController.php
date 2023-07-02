<?php

namespace DMarti\ExamplesSymfony5\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response('Examples using Symfony 5');
    }
}
