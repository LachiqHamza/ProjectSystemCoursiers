<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    #[Route('/api/admins', name: 'get_admins', methods: ['GET'])]
    public function getAdmins(AdminRepository $adminRepository): JsonResponse
    {
        $admins = $adminRepository->findAll();
        return $this->json($admins);
    }

    #[Route('/api/admins/{id}', name: 'get_admin', methods: ['GET'])]
    public function getAdmin(AdminRepository $adminRepository, int $id): JsonResponse
    {
        $admin = $adminRepository->find($id);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        return $this->json($admin);
    }

    #[Route('/api/admins', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $admin = $serializer->deserialize($data, Admin::class, 'json');

        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json($admin, 201);
    }

    #[Route('/api/admins/{id}', name: 'update_admin', methods: ['PUT'])]
    public function updateAdmin(Request $request, AdminRepository $adminRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $admin = $adminRepository->find($id);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $admin->setNom($data['nom'] ?? $admin->getNom());
        $admin->setPrenom($data['prenom'] ?? $admin->getPrenom());
        $admin->setTele($data['tele'] ?? $admin->getTele());
        $admin->setEmail($data['email'] ?? $admin->getEmail());
        $admin->setPassword($data['password'] ?? $admin->getPassword());
        $admin->setRole($data['role'] ?? $admin->getRole());
        $admin->setCin($data['Cin'] ?? $admin->getCin());
        $admin->setDateIntergration($data['date_intergration'] ?? $admin->getDateIntergration());
        $admin->setSalaire($data['salaire'] ?? $admin->getSalaire());

        $entityManager->flush();

        return $this->json($admin);
    }

    #[Route('/api/admins/{id}', name: 'delete_admin', methods: ['DELETE'])]
    public function deleteAdmin(AdminRepository $adminRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $admin = $adminRepository->find($id);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        $entityManager->remove($admin);
        $entityManager->flush();

        return $this->json(['message' => 'Admin deleted']);
    }
}
