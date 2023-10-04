<?php

namespace App\Controller;

use App\Entity\Person;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person')]
class PersonController extends AbstractController
{
    private $em;
    private $repository;

    public function __construct(private ManagerRegistry $doctrine)
    {
        $this->em = $this->doctrine->getManager();
        $this->repository = $this->doctrine->getRepository(Person::class);
    }
    #[Route('/stats/age/{min?18}/{max?65}', name: 'app_stats_person')]
    public function statsPerson($min, $max): Response
    {
        $stats = $this->repository->statsPersonneByAge($min, $max);
        return $this->render('person/stats.html.twig', ['stats' => $stats[0], 'ageMin' => $min, 'ageMax' => $max]);
    }

    #[Route('/list/{page?1}/{nbre?12}', name: 'app_list_person')]
    public function listPerson($page, $nbre): Response
    {
        // les personnes
        $this->repository->findByQuelqueChose('sellaouti');
        $personnes = $this->repository->findBy([], [], $nbre, ($page - 1) * $nbre);
        // Le nombre de personne
        $nbPersonnes = $this->repository->count([]);
        // Le nombre de page
        $nbPage = ceil($nbPersonnes / $nbre);
        return $this->render('person/index.html.twig', [
            'personnes' => $personnes,
            'nbPage' => $nbPage,
            'nbre' => $nbre,
            'page' => $page
        ]);
    }

    #[Route('/add/{name}/{age}/{cin}', name: 'app_add_person')]
    public function addPerson($name, $age, $cin): Response
    {
        $person1 = new Person();
        $person1->setName($name);
        $person1->setAge($age);
        $person1->setCin($cin);

        $this->em->persist($person1);
        $this->em->flush();
        return $this->redirectToRoute('app_list_person');

    }

    #[Route('/update/{id}/{name}/{age}', name: 'app_update_person')]
    public function updatePerson(Person $person = null,$name, $age): Response
    {
        if (!$person) {
            throw new NotFoundHttpException("Personne innexistante");
        }
        $person->setName($name);
        $person->setAge($age);

        $this->em->persist($person);
        $this->em->flush();
        return $this->redirectToRoute('app_list_person');
    }
    #[Route('/delete/{id}', name: 'app_delete_person')]
    public function deletePerson(Person $person = null): Response
    {
        if (!$person) {
            throw new NotFoundHttpException("Personne innexistante");
        }

        $this->em->remove($person);
        $this->em->flush();
        return $this->redirectToRoute('app_list_person');

    }
}
