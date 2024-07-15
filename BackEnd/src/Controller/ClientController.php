<?php

namespace App\Controller;

use App\Entity\Client;
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
    #[Route('/api/clients', name: 'get_clients', methods: ['GET'])]
    public function getClients(ClientRepository $clientRepository): JsonResponse
    {
        $clients = $clientRepository->findAll();
        return $this->json($clients);
    }

    #[Route('/api/clients/{id}', name: 'get_client', methods: ['GET'])]
    public function getClient(ClientRepository $clientRepository, int $id): JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return $this->json(['message' => 'Client not found'], 404);
        }

        return $this->json($client);
    }

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

    #[Route('/api/clients/{id}', name: 'update_client', methods: ['PUT'])]
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
