<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    // /**
    //  * @return Program[] Returns an array of Program objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Program
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function searchByTitle($value)
    {   
        $programs = $this->createQueryBuilder('p')
            ->where('p.title = :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
        var_dump($programs);
        return $programs;
        
        /*$programs = $this->getEntityManager()
            ->createQuery('SELECT p FROM App\Entity\Program p WHERE p.title LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getResult()
        ;
        var_dump($programs);
        return $programs;*/

        /*$conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT * FROM program p WHERE p.title LIKE :val';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['val' => '%'.$value.'%']);
        $programs = $stmt->fetchAll();
        var_dump($programs);
        return $programs;*/
    }
}
