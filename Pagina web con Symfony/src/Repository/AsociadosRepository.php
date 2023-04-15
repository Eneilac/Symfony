<?php

namespace App\Repository;

use App\Entity\Asociados;
use App\Entity\Imagenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asociados>
 *
 * @method Asociados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asociados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asociados[]    findAll()
 * @method Asociados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsociadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asociados::class);
    }

    public function add(Asociados $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Asociados $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAsociados($entityManager)
    {
        $asociadosRepository = $entityManager->getRepository(Asociados::class)->findAll();
        return $asociadosRepository;
    }

    public function generar3Asociados($entityManager)
    {
        $asociadosRepository = $entityManager->getRepository(Asociados::class)->findAll();

        shuffle($asociadosRepository);

        if (count($asociadosRepository) <= 3) {
            return $asociadosRepository;
        } else {
            return array_slice($asociadosRepository, 0, 3);
        }
    }

//    /**
//     * @return Asociados[] Returns an array of Asociados objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Asociados
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
