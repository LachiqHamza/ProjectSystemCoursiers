<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Coursiers;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use App\Repository\CoursiersRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ClientController extends AbstractController
{

//*******************************GET ALL CLIENT****************************************************************
    #[Route('/api/clients', name: 'get_clients', methods: ['GET'])]
    public function getClients(ClientRepository $clientRepository): JsonResponse
    {
        $clients = $clientRepository->findAll();

        $responseData = [];

        foreach ($clients as $client) {
            $clientData = [
                'id_client' => $client->getId(),
                'name' => $client->getName(),
                'lastname' => $client->getLastname(),
                'email' => $client->getEmail(),
                'tele' => $client->getTele(),
                'role' => $client->getRole(),
                'password' => $client->getPassword()
            ];

            /*$demandes = $client->getDemandes();
            if ($demandes !== null) {
                $demandesData = [];
                foreach ($demandes as $demande) {
                    $demandesData[] = [
                        'id_demande' => $demande->getIdDemande(),
                        'description' => $demande->getDescription(),
                        'adress_source' => $demande->getAdressSource(),
                        'adress_dest' => $demande->getAdressDest(),
                        'poids' => $demande->getPoids(),
                        'date_demande' => $demande->getDateDemande(),
                        'status' => $demande->getStatus(),
                        'date_livraison' => $demande->getDateLivraison()
                    ];
                }
                $clientData['demandes'] = $demandesData;
            } else {
                $clientData['demandes'] = null;
            }*/

            /*$factures = $client->getFactures();
            if ($factures !== null) {
                $clientData['$factures'] = [
                    'id_demande' => 'not now'
                ];
            } else {
                $clientData['$factures'] = null;
            }*/

            $responseData[] = $clientData;
        }

        return $this->json($responseData);
    }
//***********************************************************************************************

//***********************************************************************************************
    #[Route('/api/clients/{id}', name: 'get_client', methods: ['GET'])]
    public function getClient(ClientRepository $clientRepository, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        $clientData = [
            'id_client' => $client->getId(),
            'name' => $client->getName(),
            'lastname' => $client->getLastname(),
            'email' => $client->getEmail(),
            'tele' => $client->getTele(),
            'role' => $client->getRole(),
            'password' => $client->getPassword()
        ];
        return $this->json($clientData);
    }
//***********************************************************************************************

//********************LOGIN METHOD********************************************************
    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, ClientRepository $clientRepository, CoursiersRepository $courcierRepository, AdminRepository $adminRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['message' => 'Email and password are required'], 400);
        }

        $user = $clientRepository->findOneBy(['email' => $email]);
        if ($user) {
            if (!$password == $user->getPassword()) {
                return $this->json(['message' => 'Invalid email or password'], 401);
            }
            $userData = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'lastname' => $user->getLastname(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'tele' => $user->getTele()
            ];
            return $this->json($userData);
        }

        $user = $courcierRepository->findOneBy(['email' => $email]);
        if ($user) {
            if (!$password == $user->getPassword()) {
                return $this->json(['message' => 'Invalid email or password'], 401);
            }
            $userData = [
                'id' => $user->getIdCoursier(),
                'name' => $user->getNom(),
                'lastname' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'tele' => $user->getTele(),
                'cin' => $user->getCin(),
                'datedintegration' => $user->getDateIntergration(),
                'salaire' => $user->getSalaire()
            ];
            return $this->json($userData);
        }

        $user = $adminRepository->findOneBy(['email' => $email]);
        if ($user) {
            if (!$password == $user->getPassword()) {
                return $this->json(['message' => 'Invalid email or password'], 401);
            }
            $userData = [
                'id' => $user->getIdAdmin(),
                'name' => $user->getNom(),
                'lastname' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'tele' => $user->getTele(),
                'cin' => $user->getCin(),
                'datedintegration' => $user->getDateIntergration(),
                'salaire' => $user->getSalaire()
            ];
            return $this->json($userData);
        }

        return $this->json(['message' => 'Invalid email or password******'], 401);
    }

//***********************************************************************************************



//***********************************************************************************************
    #[Route('/api/clients/add', name: 'add_client', methods: ['POST'])]
    public function addClient(Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;
        $lastname = $data['lastname'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $tele = $data['tele'] ?? null;
        $role = $data['role'] ?? null;

        if (!$name || !$lastname || !$email || !$password || !$tele || !$role) {
            return $this->json(['message' => 'All fields are required'], 400);
        }

        // Check if the email already exists in the Client table
        $existingClient = $clientRepository->findOneBy(['email' => $email]);
        if ($existingClient) {
            return $this->json(['message' => 'Email already exists'], 409);
        }

        // Create a new client
        $client = new Client($name, $lastname, $email, $password, $tele, $role);

        // Persist the new client to the database
        $entityManager->persist($client);
        $entityManager->flush();

        // Return the new client data
        $clientData = [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'lastname' => $client->getLastname(),
            'email' => $client->getEmail(),
            'tele' => $client->getTele(),
            'role' => $client->getRole()
        ];

        return $this->json($clientData, 201);
    }


//***********************************************************************************************


//*********************************UPDATE CLIENT**************************************************************
    #[Route('/api/clients/{id}/update', name: 'update_client', methods: ['PUT'])]
    public function updateClient(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $client = $entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        // Update the client entity with new data
        $client->setName($data['name'] ?? $client->getName());
        $client->setLastname($data['lastname'] ?? $client->getLastname());
        $client->setTele($data['tele'] ?? $client->getTele());
        $client->setPassword($data['password'] ?? $client->getPassword());

        // Persist the updated entity to database
        $entityManager->persist($client);
        $entityManager->flush();

        // Prepare the response data
        $clientData = [
            'id_client' => $client->getId(),
            'name' => $client->getName(),
            'lastname' => $client->getLastname(),
            'email' => $client->getEmail(),
            'tele' => $client->getTele(),
            'role' => $client->getRole(),
            'password' => $client->getPassword()
        ];

        return $this->json($clientData);
    }
//***********************************************************************************************


//***********************************************************************************************
//***********************************************************************************************


    #[Route('/api/clients', name: 'create_client', methods: ['POST'])]
    public function createClient(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();

        try {
            $client = $serializer->deserialize($data, Client::class, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'message' => 'Invalid JSON format: ' . $e->getMessage()
            ], 400);
        }

        $entityManager->persist($client);
        $entityManager->flush();

        return $this->json($client, 201);
    }

    /*#[Route('/api/clients/{id}', name: 'update_client', methods: ['PUT'])]
    public function updateClient(Request $request, ClientRepository $clientRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $client->setName($data['name'] ?? $client->getName());
        $client->setLastname($data['lastname'] ?? $client->getLastname());
        $client->setEmail($data['email'] ?? $client->getEmail());
        $client->setPassword($data['password'] ?? $client->getPassword());
        $client->setTele($data['tele'] ?? $client->getTele());
        $client->setRole($data['role'] ?? $client->getRole());

        $entityManager->flush();

        return $this->json($client);
    }
*/
    #[Route('/api/clients/{id}', name: 'delete_client', methods: ['DELETE'])]
    public function deleteClient(ClientRepository $clientRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        $entityManager->remove($client);
        $entityManager->flush();

        return $this->json(['message' => 'Client deleted']);
    }
}
