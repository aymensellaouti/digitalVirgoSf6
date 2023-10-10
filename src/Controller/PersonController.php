<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\services\FirstService;
use App\services\UploaderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/person')]
class PersonController extends AbstractController
{
    private $em;
    private $repository;

    private string $appName;

    public function __construct(private ManagerRegistry $doctrine, $appName)
    {
        $this->appName = $appName;

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
    public function listPerson($page, $nbre, FirstService $firstService): Response
    {
        //dd($this->appName);
        // les personnes
        $firstService->loger('Digitalvirgo');
        //$this->repository->findByQuelqueChose('sellaouti');
        $personnes = $this->repository->findBy([], ['createdAt' => 'DESC'], $nbre, ($page - 1) * $nbre);
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

    #[Route('/addfake/{name}/{age}/{cin}', name: 'app_add_fake_person')]
    public function addFakePerson($name, $age, $cin): Response
    {
        $person1 = new Person();
        $person1->setName($name);
        $person1->setAge($age);
        $person1->setCin($cin);

        $this->em->persist($person1);
        $this->em->flush();
        return $this->redirectToRoute('app_list_person');

    }

    #[Route('/edit/{id?0}', name: 'app_add_person')]
    public function addPerson(Request $request, UploaderService $uploaderService, Person $person = null): Response
    {
        $isNew = false;
        if(!$person) {
            $person = new Person();
            $isNew = true;
        }
        $form = $this->createForm(PersonType::class, $person, /*[
            'action' => $this->generateUrl('app_delete_person', ["id" => 10]),
            'method' => 'GET'
        ]*/);
        $form->remove('createdAt');
        $form->remove('updatedAt');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $path */
            $path = $form->get('image')->getData();
            if ($path) {
                $directory = $this->getParameter('person_directory');
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $person->setPath($uploaderService->uploadFile($path, $directory));
            }
            $this->em->persist($person);
            $this->em->flush();
            return $this->redirectToRoute('app_list_person');
        }
        return $this->render('person/add.hml.twig', [
            "form" => $form->createView()
        ]);

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
