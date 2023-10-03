<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request, SessionInterface $session): Response
    {
        // Si premier accès on initilaise la session
        if (!$session->has('todos')) {
            $todos = array(
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens');
            $session->set('todos', $todos);
            $this->addFlash('info', 'Liste initialisé avec succès');
        }
        // Sinon rien de special
        return $this->render('todo/index.html.twig');
    }
    #[Route('/add/{name}/{description?fakeDescription}', name: 'app_add_todo')]
    public function addTodo(
        Request $request,
        SessionInterface $session,
        $name,
        $description
    ): Response
    {
        // Si pas de session
        if (!$session->has('todos')) {
            $this->addFlash('error', 'Session non encore initialisée');
        } else {
            $todos = $session->get('todos');
            // si todo existe déjà
            if(isset($todos[$name])) {
                $this->addFlash('error', "Todo $name existe déjà ");
            } else {
                $todos[$name] = $description;
                $session->set('todos', $todos);
                $this->addFlash('success', "Todo $name ajouté avec succès ");
            }
        }
        // Sinon rien de special
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/update/{name}/{description}', name: 'app_update_todo')]
    public function updateTodo(
        Request $request,
        SessionInterface $session,
        $name,
        $description
    ): Response
    {
        // Si pas de session
        if (!$session->has('todos')) {
            $this->addFlash('error', 'Session non encore initialisée');
        } else {
            $todos = $session->get('todos');
            // si todo existe déjà
            if(!isset($todos[$name])) {
                $this->addFlash('error', "Todo $name n'existe pas ");
            } else {
                $todos[$name] = $description;
                $session->set('todos', $todos);
                $this->addFlash('success', "Todo $name mis à jour avec succès ");
            }
        }
        // Sinon rien de special
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/delete/{name}', name: 'app_delete_todo')]
    public function deleteTodo(
        Request $request,
        SessionInterface $session,
        $name
    ): Response
    {
        // Si pas de session
        if (!$session->has('todos')) {
            $this->addFlash('error', 'Session non encore initialisée');
        } else {
            $todos = $session->get('todos');
            // si todo existe déjà
            if(!isset($todos[$name])) {
                $this->addFlash('error', "Todo $name n'existe pas ");
            } else {
                unset($todos[$name]);
                $session->set('todos', $todos);
                $this->addFlash('success', "Todo supprimé avec succès ");
            }
        }
        // Sinon rien de special
        return $this->redirectToRoute('app_todo');
    }
}

