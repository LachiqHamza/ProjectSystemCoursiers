<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Entity\Client;
use App\Entity\Admin;
use App\Entity\Coursiers;
use App\Repository\DemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;


#[Route('/api/demandes')]
class DemandeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private DemandeRepository $demandeRepository;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, DemandeRepository $demandeRepository, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->demandeRepository = $demandeRepository;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'get_demandes', methods: ['GET'])]
    public function getDemandes(): JsonResponse
    {
        $demandes = $this->demandeRepository->findAll();
        return $this->json($demandes);
    }

    #[Route('/{id}', name: 'get_demande', methods: ['GET'])]
    public function getDemande(int $id): JsonResponse
    {
        $demande = $this->demandeRepository->find($id);

        if (!$demande) {
            throw new NotFoundHttpException('Demande not found');
        }

        return $this->json($demande);
    }



    #[Route('/{id}', name: 'update_demande', methods: ['PUT'])]
    public function updateDemande(Request $request, int $id): JsonResponse
    {
        $demande = $this->demandeRepository->find($id);

        if (!$demande) {
            throw new NotFoundHttpException('Demande not found');
        }

        try {
            $data = json_decode($request->getContent(), true);

            $demande->setAdressSource($data['adress_source'] ?? $demande->getAdressSource());
            $demande->setAdressDest($data['adress_dest'] ?? $demande->getAdressDest());
            $demande->setPoids($data['poids'] ?? $demande->getPoids());

            // Handle DateTime separately
            $dateDemande = isset($data['date_demande']) ? new \DateTime($data['date_demande']) : $demande->getDateDemande();
            $demande->setDateDemande($dateDemande);

            $demande->setStatus($data['status'] ?? $demande->getStatus());

            // Handle DateTime separately
            $dateLivraison = isset($data['date_livraison']) ? new \DateTime($data['date_livraison']) : $demande->getDateLivraison();
            $demande->setDateLivraison($dateLivraison);

            $this->entityManager->flush();

            return $this->json($demande);
        } catch (NotEncodableValueException $e) {
            return $this->json(['message' => 'Invalid JSON format'], 400);
        } catch (InvalidArgumentException $e) {
            return $this->json(['message' => 'Invalid data provided'], 400);
        }
    }

    #[Route('/{id}', name: 'delete_demande', methods: ['DELETE'])]
    public function deleteDemande(int $id): JsonResponse
    {
        $demande = $this->demandeRepository->find($id);

        if (!$demande) {
            throw new NotFoundHttpException('Demande not found');
        }

        $this->entityManager->remove($demande);
        $this->entityManager->flush();

        return $this->json(['message' => 'Demande deleted']);
    }


//**********************************************************************************************************
    #[Route('/add', name: 'create_demande', methods: ['POST'])]
    public function createDemande(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $client = $this->entityManager->getRepository(Client::class)->find($data['client']['id_client']);
            $admin = isset($data['$id_admin']['id_admin']) ? $this->entityManager->getRepository(Admin::class)->find($data['$id_admin']['id_admin']) : null;
            $coursier = isset($data['coursier']['id_coursier']) ? $this->entityManager->getRepository(Coursiers::class)->find($data['coursier']['id_coursier']) : null;

            $demande = new Demande();
            $demande->setDescription($data['description']);
            $demande->setClient($client);
            $demande->setIdAdmin($admin);
            $demande->setCoursier($coursier);
            $demande->setAdressSource($data['adress_source']);
            $demande->setAdressDest($data['adress_dest']);
            $demande->setPoids($data['poids']);
            $demande->setDateDemande(new \DateTime($data['date_demande']));
            $demande->setStatus($data['status'] ?? null);
            $demande->setDateLivraison($data['date_livraison'] ? new \DateTime($data['date_livraison']) : null);

            $this->entityManager->persist($demande);
            $this->entityManager->flush();

            return $this->json($demande, 201);
        } catch (NotEncodableValueException $e) {
            return $this->json(['message' => 'Invalid JSON format'], 400);
        } catch (InvalidArgumentException $e) {
            return $this->json(['message' => 'Invalid data provided'], 400);
        }
    }

//**********************************************************************************************************

//**********************************************************************************************************
    #[Route('/demandes/{coursierId}', name: 'demandes_by_coursier', methods: ['GET'])]
    public function getDemandesByCoursier(int $coursierId, DemandeRepository $demandeRepository): JsonResponse
    {
        $demandes = $demandeRepository->findAllDemandesByCoursierAndStatusAccepter($coursierId);

        $responseData = [];

        foreach ($demandes as $demande) {
            $demandeData = [
                'id_demande' => $demande->getIdDemande(),
                'description' => $demande->getDescription(),
                'adress_source' => $demande->getAdressSource(),
                'adress_dest' => $demande->getAdressDest(),
                'poids' => $demande->getPoids(),
                'date_demande' => $demande->getDateDemande(),
                'status' => $demande->getStatus(),
                'date_livraison' => $demande->getDateLivraison(),
            ];


            $admin = $demande->getIdAdmin();
            if ($admin !== null) {
                $demandeData['admin'] = [
                    'id_admin' => $admin->getIdAdmin(),
                    'admin_name' => $admin->getNom(),
                    'admin_prenom' => $admin->getPrenom(),
                    'admin_telephone' => $admin->getTele(),
                    'admin_email' => $admin->getEmail(),
                    'admin_role' => $admin->getRole(),
                    'admin_cin' => $admin->getCin(),
                    'admin_integrationdate' => $admin->getDateIntergration(),
                    'admin_salary' => $admin->getSalaire(),
                ];
            } else {
                $demandeData['admin'] = null;
            }


            $client = $demande->getClient();
            if ($client !== null) {
                $demandeData['client'] = [
                    'id_client' => $client->getId(),
                    'client_name' => $client->getName(),
                    'client_lastName' => $demande->getClient()->getLastname(),
                    'client_email' => $demande->getClient()->getEmail(),
                    'client_phone' => $demande->getClient()->getTele(),
                    'client_role' => $demande->getClient()->getRole(),

                ];
            } else {
                $demandeData['client'] = null;
            }

            $responseData[] = $demandeData;
        }

        return $this->json($responseData);
    }
//*******************************************************************************************************

//*******************************************************************************************************
    #[Route('/demandes/{id_demande}/updatedatelivraison/{date}', name: 'update_date_livraison', methods: ['PUT'])]
    public function updateDateLivraison(int $id_demande, string $date, DemandeRepository $demandeRepository): JsonResponse
    {
        try {
            $dateLivraison = \DateTime::createFromFormat('Y-m-d', $date);

            if (!$dateLivraison) {
                throw new \Exception('Invalid date format. Expected Y-m-d.');
            }

            $demandeRepository->updateDateLivraison($id_demande, $dateLivraison);

            return $this->json(['message' => 'Date livraison updated successfully.']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

//*******************************************************************************************************

//*******************************************************************************************************
    #[Route('/finddemandes/newdemandes', name: 'demandes_null_admin_coursier', methods: ['GET'])]
    public function getDemandesWithNullAdminAndCoursier(): JsonResponse
    {
        $demandes = $this->demandeRepository->findNweDemandesNullAdminAndCoursier();

        $responseData = [];

        foreach ($demandes as $demande) {
            $demandeData = [
                'id_demande' => $demande->getIdDemande(),
                'description' => $demande->getDescription(),
                'client' => $demande->getClient() ? [
                    'id_client' => $demande->getClient()->getId(),
                    'client_name' => $demande->getClient()->getName(),
                    'client_lastName' => $demande->getClient()->getLastname(),
                    'client_email' => $demande->getClient()->getEmail(),
                    'client_phone' => $demande->getClient()->getTele(),
                    'client_role' => $demande->getClient()->getRole(),
                ] : null,
                'id_admin' => $demande->getIdAdmin() ? $demande->getIdAdmin()->getIdAdmin() : null,
                'coursier' => $demande->getCoursier() ? $demande->getCoursier()->getIdCoursier() : null,
                'adress_source' => $demande->getAdressSource(),
                'adress_dest' => $demande->getAdressDest(),
                'poids' => $demande->getPoids(),
                'date_demande' => $demande->getDateDemande() ? $demande->getDateDemande()->format('Y-m-d') : null,
                'status' => $demande->getStatus(),
                'date_livraison' => $demande->getDateLivraison() ? $demande->getDateLivraison()->format('Y-m-d') : null,
            ];

            $responseData[] = $demandeData;
        }

        return new JsonResponse($responseData);
    }

//*******************************************************************************************************


//*******************************************************************************************************
    #[Route('/finddemandes/newdemandes/count', name: 'demandes_null_admin_coursier_count', methods: ['GET'])]
    public function countDemandesWithNullAdminAndCoursier(DemandeRepository $demandeRepository): JsonResponse
    {
        try {
            $count = $demandeRepository->countNweDemandesNullAdminAndCoursier();

            return $this->json(['count' => $count]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

//*******************************************************************************************************
}
