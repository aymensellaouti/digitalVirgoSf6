<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\CustomLoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/user/create', name: 'admin_user_create')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, CustomLoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('agreeTerms');
        $form->add('roles', ChoiceType::class, [
            'choices' => [
                'user' => 'ROLE_USER',
                'admin' => 'ROLE_ADMIN',
            ],
            'multiple' => true
        ]);
        $form->add('add', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            return $this->redirectToRoute('admin_user_create');
        }
        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
