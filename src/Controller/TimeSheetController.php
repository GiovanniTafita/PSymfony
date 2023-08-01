<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Entity\TimeSheet;
use App\Repository\TimeSheetRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class TimeSheetController extends AbstractController
{
    private $serializer;
    private $entityManager;
    private $repository;
    private $response;

    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        TimeSheetRepository $repository
    ) {
        $this->serializer = $serializer;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/time_sheet', name: 'app_time_sheet', methods: 'GET')]
    public function getTimeSheets(): JsonResponse
    {
        $timeSheets = $this->repository->findAll();
        $jsonTimeSheets = $this->serializer->serialize($timeSheets, 'json');

        return new JsonResponse($jsonTimeSheets, Response::HTTP_OK, [], true);
    }

    #[Route('/api/time_sheet', name: 'add_time_sheet', methods: 'POST')]
    public function createTimeSheets(Request $request): JsonResponse
    {
        $timeSheet = $this->serializer->deserialize($request->getContent(), TimeSheet::class, 'json');

        $this->entityManager->persist($timeSheet);
        $this->entityManager->flush();

        $jsonTimeSheet = $this->serializer->serialize($timeSheet, 'json');

        return new JsonResponse($jsonTimeSheet, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/time_sheet/{id}', name: 'edit_time_sheet', methods: 'PUT')]
    public function updateTimeSheets(Request $request, TimeSheet $currentTimeSheet): JsonResponse
    {
        $updatedTimeSheet = $this->serializer->deserialize(
            $request->getContent(),
            TimeSheet::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentTimeSheet]
        );

        $this->entityManager->persist($updatedTimeSheet);
        $this->entityManager->flush();

        $jsonTimeSheet = $this->serializer->serialize($updatedTimeSheet, 'json');

        return new JsonResponse($jsonTimeSheet, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/time_sheet/match/{id}', name: 'match_time_sheet', methods: 'POST')]
    public function matchTimeSheet(TimeSheet $timeSheet)
    {
        $startDate = $timeSheet->getStartAt();
        $endDate = $timeSheet->getEndAt();
        $daysDiff = 0;
        if (!$startDate || !$endDate) {
            return new JsonResponse(["message" => "no date to match"]);
        } else {
            $daysDiff = $startDate->diff($endDate)->days;
            $horaires = [];
            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = $currentDate->modify('+1 day')) {
                $newHoraire = new Horaire();
                $newHoraire->setDate($currentDate->format('Y-m-d'));
                // $this->entityManager->persist($newHoraire);

                $timeSheet->addHoraire($newHoraire);

                array_push($horaires, $newHoraire);
            }

            // $this->entityManager->persist($timeSheet);
            // $this->entityManager->flush();
            $json = $this->serializer->serialize($timeSheet, 'json', ['groups' => 'getTimeSheet']);
            return new JsonResponse($json, Response::HTTP_CREATED, [], true);
        }
    }
}
