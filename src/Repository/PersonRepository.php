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
     * @return Person[] Returns an array of Person objects
     */
    public function findByQuelqueChose($value): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.name = :name')
            ->setParameter('name', $value);
        return $qb
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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
