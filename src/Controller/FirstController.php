<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/', name: 'hello')]
    public function hello()
    {
        $content = "<html><body><h1>Hello</h1></body></html>";
        $response = new Response();
        $response->setContent($content);
        return $response;
    }

}
