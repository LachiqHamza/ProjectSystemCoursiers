<?php

namespace App\Controller;

use App\Entity\Coursiers;
use App\Repository\CoursiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CoursiersController extends AbstractController
{
    #[Route('/api/coursiers', name: 'get_coursiers', methods: ['GET'])]
    public function getCoursiers(CoursiersRepository $coursiersRepository): JsonResponse
    {
        $coursiers = $coursiersRepository->findAll();
        return $this->json($coursiers);
    }

    #[Route('/api/coursiers/{id}', name: 'get_coursier', methods: ['GET'])]
    public function getCoursier(CoursiersRepository $coursiersRepository, int $id): JsonResponse
    {
        $coursier = $coursiersRepository->find($id);

        if (!$coursier) {
            return $this->json(['message' => 'Coursier not found'], 404);
        }

        return $this->json($coursier);
    }

    #[Route('/api/coursiers', name: 'create_coursier', methods: ['POST'])]
    public function createCoursier(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $coursier = $serializer->deserialize($data, Coursiers::class, 'json');

        $entityManager->persist($coursier);
        $entityManager->flush();

        return $this->json($coursier, 201);
    }

    #[Route('/api/coursiers/{id}', name: 'update_coursier', methods: ['PUT'])]
    public function updateCoursier(Request $request, CoursiersRepository $coursiersRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $coursier = $coursiersRepository->find($id);

        if (!$coursier) {
            return $this->json(['message' => 'Coursier not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $coursier->setNom($data['nom'] ?? $coursier->getNom());
        $coursier->setPrenom($data['prenom'] ?? $coursier->getPrenom());
        $coursier->setTele($data['tele'] ?? $coursier->getTele());
        $coursier->setEmail($data['email'] ?? $coursier->getEmail());
        $coursier->setPassword($data['password'] ?? $coursier->getPassword());
        $coursier->setRole($data['role'] ?? $coursier->getRole());
        $coursier->setCin($data['Cin'] ?? $coursier->getCin());

        // Handle DateTime separately
        $dateIntegration = isset($data['date_intergration']) ? new \DateTime($data['date_intergration']) : $coursier->getDateIntergration();
        $coursier->setDateIntergration($dateIntegration);

        $coursier->setSalaire($data['salaire'] ?? $coursier->getSalaire());

        $entityManager->flush();

        return $this->json($coursier);
    }

    #[Route('/api/coursiers/{id}', name: 'delete_coursier', methods: ['DELETE'])]
    public function deleteCoursier(CoursiersRepository $coursiersRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $coursier = $coursiersRepository->find($id);

        if (!$coursier) {
            return $this->json(['message' => 'Coursier not found'], 404);
        }

        $entityManager->remove($coursier);
        $entityManager->flush();

        return $this->json(['message' => 'Coursier deleted']);
    }


//*******************************************************************
    #[Route('/api/courciers/add', name: 'add_courcier', methods: ['POST'])]
    public function addCourcier(Request $request, CoursiersRepository $courcierRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $tele = $data['tele'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $role = $data['role'] ?? null;
        $cin = $data['Cin'] ?? null;
        $dateIntegration = isset($data['Date_intergration']) ? new \DateTime($data['Date_intergration']) : null;
        $salaire = isset($data['salaire']) ? floatval($data['salaire']) : null;

        if (!$nom || !$prenom || !$tele || !$email || !$password || !$role || !$cin || !$dateIntegration || !$salaire) {
            return $this->json(['message' => 'All fields are required'], 400);
        }

        // Check if the email already exists in the Courcier table
        $existingCourcier = $courcierRepository->findOneBy(['email' => $email]);
        if ($existingCourcier) {
            return $this->json(['message' => 'Email already exists'], 409);
        }


        // Create a new courcier
        $courcier = new Coursiers($nom, $prenom, $tele, $email, $password, $role, $cin, $dateIntegration, $salaire);

        // Persist the new courcier to the database
        $entityManager->persist($courcier);
        $entityManager->flush();

        // Return the new courcier data
        $courcierData = [
            'id' => $courcier->getIdCoursier(),
            'nom' => $courcier->getNom(),
            'prenom' => $courcier->getPrenom(),
            'tele' => $courcier->getTele(),
            'email' => $courcier->getEmail(),
            'role' => $courcier->getRole(),
            'Cin' => $courcier->getCin(),
            'Date_intergration' => $courcier->getDateIntergration()->format('Y-m-d'),
            'salaire' => $courcier->getSalaire()
        ];

        return $this->json($courcierData, 201);
    }
//*******************************************************************



//***************************GET ALL COOURCIERS****************************************
    #[Route('/api/courciers/all', name: 'get_all_courciers', methods: ['GET'])]
    public function getAllCourciers(CoursiersRepository $coursiersRepository): JsonResponse
    {
        $courciers = $coursiersRepository->findAll();

        $courciersData = [];
        foreach ($courciers as $courcier) {
            $courciersData[] = [
                'id' => $courcier->getIdCoursier(),
                'name' => $courcier->getNom(),
                'lastname' => $courcier->getPrenom(),
                'email' => $courcier->getEmail(),
                'role' => $courcier->getRole(),
                'tele' => $courcier->getTele(),
                'cin' => $courcier->getCin(),
                'datedintegration' => $courcier->getDateIntergration(),
                'salaire' => $courcier->getSalaire(),
            ];
        }

        return $this->json($courciersData);
    }
//*******************************************************************

}
