<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ChampionsRepository;

final class ChampionsController extends AbstractController
{
    #[Route('/api/champions', name: 'app_champions')]
    public function index(ChampionsRepository $championRepository): JsonResponse
    {
        $champions = $championRepository->findAll();

        $data = array_map(function(\App\Entity\Champions $c) {
            return [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'gender' => $c->getGender(),
                'role' => $c->getRole(),
                'species' => $c->getSpecies(),
                'resource' => $c->getResource(),
                'range_type' => $c->getRangeType(),
                'region' => $c->getRegion(),
                'release_year' => $c->getReleaseYear(),
            ];
        }, $champions);

        return $this->json([
            'data' => $data
        ]);
    }
}
