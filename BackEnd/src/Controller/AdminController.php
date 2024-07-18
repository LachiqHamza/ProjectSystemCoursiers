<?php

namespace App\Controller;

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

    /*#[Route('/api/admins/{id}', name: 'update_admin', methods: ['PUT'])]
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
*/
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

 //******************************UPDATE ADMIN***************************************************
    #[Route('/api/admins/{id}/update', name: 'update_admin', methods: ['PUT'])]
    public function updateAdmin(int $id, Request $request, AdminRepository $adminRepository, CoursiersRepository $coursiersRepository, ClientRepository $clientRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $admin = $entityManager->getRepository(Admin::class)->find($id);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $newEmail = $data['email'] ?? null;

        // Check if the new email already exists, ignoring the current admin's email
        if ($newEmail && $newEmail !== $admin->getEmail()) {
            $existingAdmin = $adminRepository->findOneBy(['email' => $newEmail]);
            if ($existingAdmin) {
                return $this->json(['message' => 'Email already exists in admin table'], 409);
            }
            $existingCoursier = $coursiersRepository->findOneBy(['email' => $newEmail]);
            if ($existingCoursier) {
                return $this->json(['message' => 'Email already exists in coursier table'], 409);
            }
            $existingCoursier = $clientRepository->findOneBy(['email' => $newEmail]);
            if ($existingCoursier) {
                return $this->json(['message' => 'Email already exists in client table'], 409);
            }
        }

        // Update the admin entity with new data
        $admin->setNom($data['nom'] ?? $admin->getNom());
        $admin->setPrenom($data['prenom'] ?? $admin->getPrenom());
        $admin->setTele($data['tele'] ?? $admin->getTele());
        $admin->setEmail($data['email'] ?? $admin->getEmail());
        $admin->setPassword($data['password'] ?? $admin->getPassword());

        // Persist the updated entity to the database
        $entityManager->persist($admin);
        $entityManager->flush();

        // Prepare the response data
        $adminData = [
            'id_admin' => $admin->getIdAdmin(),
            'nom' => $admin->getNom(),
            'prenom' => $admin->getPrenom(),
            'tele' => $admin->getTele(),
            'email' => $admin->getEmail(),
            'password' => $admin->getPassword(),
            'role' => $admin->getRole(),
            'Cin' => $admin->getCin(),
            'date_intergration' => $admin->getDateIntergration(),
            'salaire' => $admin->getSalaire(),
        ];

        return $this->json($adminData);
    }

    //*********************************************************************************
}
