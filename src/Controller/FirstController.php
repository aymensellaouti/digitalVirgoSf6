<?php

namespace App\Controller;

use App\services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    #[Route('/mail', name: 'mailer')]
    public function mail(MailerService $mailerService)
    {
        $mailerService->sendEmail();
        $content = "<html><body><h1>Hello</h1></body></html>";
        $response = new Response();
        $response->setContent($content);
        return $response;
    }
    #[Route('/form', name: 'form')]
    public function formulaire()
    {
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('name', TextType::class, [])
             ->add('age', NumberType::class, [])
            ->add('submit', SubmitType::class)
        ;
        return $this->render('form/index.html.twig', [
            'form' => $formBuilder->getForm()->createView()
        ]);
    }

}
