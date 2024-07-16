<?php

namespace App\Repository;

use App\Entity\Demande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demande>
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
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
    /**
     * @param int $coursierId
     * @return Demande[]
     */
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
    public function updateDateLivraison(int $id_demande, \DateTime $dateLivraison): void
    {

        $entityManager = $this->getEntityManager();
        $demande = $entityManager->getRepository(Demande::class)->find($id_demande);

        if (!$demande) {
            throw new \Exception('Demande with ID '.$id_demande.' not found.');
        }

        $demande->setDateLivraison($dateLivraison);

        $entityManager->flush();
    }
//********************************************************************************************************


}
