<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Stats;
use App\Repository\StatsRepository;
use Doctrine\ORM\EntityManagerInterface;

final class StatsController extends AbstractController
{

    #[Route('/api/stats', name: 'api_stats_save', methods: ['POST'])]
    public function saveAttempt(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $userId = $data['userId'] ?? null;
        $championId = $data['championId'] ?? null;
        $gamemode = $data['gamemode'] ?? 'classic';
        $attempts = $data['attempts'] ?? [];
        $successful = $data['successful'] ?? false;

        if (!$userId || !$championId) {
            return new JsonResponse(['error' => 'userId and championId are required'], 400);
        }

        $stat = new Stats();
        $stat->setIdUser($userId);
        $stat->setIdChampion($championId);
        $stat->setGamemode($gamemode);
        $stat->setDate(new \DateTime());
        $stat->setNbTry(count($attempts));
        $stat->setSuccessful($successful);
        $stat->setAttempts($attempts);

        $em->persist($stat);
        $em->flush();

        return new JsonResponse(['id' => $stat->getId(), 'message' => 'Attempt saved'], 201);
    }

    #[Route('/api/stats/user/{userId}', name: 'api_stats_get', methods: ['GET'])]
    public function getUserStats(int $userId, StatsRepository $statsRepository): JsonResponse
    {
        $stats = $statsRepository->findBy(['id_user' => $userId]);

        $data = array_map(function(Stats $s) {
            return [
                'id' => $s->getId(),
                'gamemode' => $s->getGamemode(),
                'date' => $s->getDate()?->format('Y-m-d H:i:s'),
                'nb_try' => $s->getNbTry(),
                'successful' => $s->getSuccessful(),
                'attempts' => $s->getAttempts() ?? [],
                'id_champion' => $s->getIdChampion(),
            ];
        }, $stats);

        return $this->json(['data' => $data]);
    }
}
