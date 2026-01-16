<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UsersRepository;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

final class AuthController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request, UsersRepository $usersRepository): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            return new JsonResponse(['error' => 'Email and password are required'], 400);
        }

        $user = $usersRepository->findOneBy(['email' => $email]);

        if (!$user || $user->getPassword() !== $password) {
            return new JsonResponse(['error' => 'Identifiants invalides'], 401);
        }

        return new JsonResponse(['userId' => $user->getId()]);
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UsersRepository $usersRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $pseudo = $data['pseudo'] ?? '';

        if (empty($email) || empty($password) || empty($pseudo)) {
            return new JsonResponse(['error' => 'Email, password and pseudo are required'], 400);
        }

        $existingUser = $usersRepository->findOneBy(['email' => $email]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Email already exists'], 409);
        }

        $user = new Users();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setPseudo($pseudo);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['userId' => $user->getId(), 'message' => 'User created successfully'], 201);
    }

    #[Route('/api/user/{userId}', name: 'api_user_get', methods: ['GET'])]
    public function getUserData(int $userId, UsersRepository $usersRepository): JsonResponse
    {
        $user = $usersRepository->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
        ]);
    }
}
