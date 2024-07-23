<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Scalar\String_;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $entityManager;



    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Demande::class);
        $this->entityManager = $entityManager;
    }

    //    /**
    //     * @return Demande[] Returns an array of Demande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Demande
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


//******************************************************************************************

    public function findAllDemandesByCoursierAndStatusAccepter(int $coursierId): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.coursier = :coursierId')
            ->andWhere('d.status = :status')
            ->andWhere('d.date_livraison IS NULL')
            ->setParameter('coursierId', $coursierId)
            ->setParameter('status', 'accepter')
            ->getQuery()
            ->getResult();
    }
//******************************************************************************************

//******************************************************************************************
    public function updateDateLivraison(int $id_demande, \DateTimeInterface $dateLivraison): void
    {
        $demande = $this->entityManager->find(Demande::class, $id_demande);

        if (!$demande) {
            throw new \Exception('Demande with ID '.$id_demande.' not found.');
        }

        $demande->setDateLivraison($dateLivraison);

        $this->entityManager->flush();
    }
//********************************************************************************************************


//********************************************************************************************************
    public function findNweDemandesNullAdminAndCoursier(): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.admin IS NULL')
            ->andWhere('d.coursier IS NULL')
            ->getQuery()
            ->getResult();
    }

//********************************************************************************************************


//********************************************************************************************************
    public function countNweDemandesNullAdminAndCoursier(): int
    {
        $query = $this->createQueryBuilder('d')
            ->select('COUNT(d.id_demande)')
            ->where('d.admin IS NULL')
            ->andWhere('d.coursier IS NULL')
            ->getQuery();

        return (int) $query->getSingleScalarResult();
    }
//********************************************************************************************************


}
