<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Person>
 *
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * Cette fonction permet d'ajouter dans la requete des contraintes sur l'age min et max
     * @param QueryBuilder $qb
     * @param $min
     * @param $max
     * @return QueryBuilder
     */
    private function addIntervalAge(QueryBuilder $qb, $min = null, $max = null): QueryBuilder
    {
        if ($min) {
            $qb->andWhere('p.age >= :min')
                ->setParameter('min', $min);
        }
        if ($max) {
            $qb->andWhere('p.age <= :max')
                ->setParameter('max', $max);
        }
        return $qb;
    }

    public function statsPersonneByAge($ageMin, $ageMax)
    {
        $qb = $this->createQueryBuilder('p');
        return
            $this->addIntervalAge($qb,$ageMin, $ageMax)
                 ->select('avg(p.age) as AgeMoyen, count(p.id) as nombrePersonne')
                 ->getQuery()
                 ->getScalarResult()
        ;
    }
}
