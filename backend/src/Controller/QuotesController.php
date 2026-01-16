<?php

namespace App\Controller;

use App\Repository\QuotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class QuotesController extends AbstractController
{
    #[Route('/api/quotes/{championName}', name: 'app_quotes')]
    public function index(string $championName, QuotesRepository $quotesRepository): JsonResponse
    {
        $quotes = $quotesRepository->findBy(['name_champion' => $championName]);

        $data = array_map(function ($quote) {
            return [
                'id' => $quote->getId(),
                'name' => $quote->getNameChampion(),
                'quote' => $quote->getQuote(),
            ];
        }, $quotes);

        return $this->json([
            'data' => $data,
        ]);
    }
}
