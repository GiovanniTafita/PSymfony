<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Service\HoraireService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class HoraireController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private HoraireService $horaireService
    ) {
    }

    #[Route('api/horaire/update/{id}', name: 'update_horaire', methods: ['POST'])]
    public function updateHoraire(Request $request, Horaire $currentHoraire): JsonResponse
    {
        $updatedHoraire = $this->serializer->deserialize(
            $request->getContent(),
            Horaire::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentHoraire]
        );

        $this->horaireService->saveHoraire($updatedHoraire);

        $jsonHoraire = $this->serializer->serialize($updatedHoraire, 'json');

        return new JsonResponse($jsonHoraire, Response::HTTP_CREATED, [], true);
    }

    #[Route('api/horaire/start/{id}', name: 'start_horaire', methods: ['POST'])]
    public function startHoraire(Horaire $horaire)
    {
        $this->horaireService->start($horaire);

        $jsonHoraire = $this->serializer->serialize($horaire, 'json', ['groups' => 'getHoraire']);

        return new JsonResponse($jsonHoraire, Response::HTTP_OK, [], true);
    }

    #[Route('api/horaire/end/{id}', name: 'end_horaire', methods: ['POST'])]
    public function endHoraire(Horaire $horaire)
    {
        $this->horaireService->end($horaire);
        $this->horaireService->totalTime($horaire);

        $jsonHoraire = $this->serializer->serialize($horaire, 'json', ['groups' => 'getHoraire']);

        return new JsonResponse($jsonHoraire, Response::HTTP_OK, [], true);
    }
}
