<?php

namespace App\Controller;

use App\Entity\Horaire;
use App\Entity\TimeSheet;
use App\Repository\TimeSheetRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Service\TimeSheetService;
use App\Service\UserService;

class TimeSheetController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private TimeSheetService $timeSheetService,
        private UserService $userService
    ) {
    }

    #[Route('/api/time_sheet', name: 'app_time_sheet', methods: 'GET')]
    public function getTimeSheets(): JsonResponse
    {
        $timeSheets = $this->timeSheetService->getTimeSheets();
        $jsonTimeSheets = $this->serializer->serialize($timeSheets, 'json', ['groups' => 'getTimeSheet']);

        return new JsonResponse($jsonTimeSheets, Response::HTTP_OK, [], true);
    }

    #[Route('/api/time_sheet/{id}', name: 'get_time_sheet', methods: 'GET')]
    public function getTimeSheet(TimeSheet $timeSheet)
    {
        $json = $this->serializer->serialize($timeSheet, 'json', ['groups' => 'getTimeSheet']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/time_sheet', name: 'add_time_sheet', methods: 'POST')]
    public function createTimeSheets(Request $request): JsonResponse
    {
        $timeSheet = $this->serializer->deserialize($request->getContent(), TimeSheet::class, 'json');
        $this->timeSheetService->saveTimeSheet($timeSheet);

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

        $this->timeSheetService->saveTimeSheet($updatedTimeSheet);

        $jsonTimeSheet = $this->serializer->serialize($updatedTimeSheet, 'json', ['groups' => 'getTimeSheet']);

        return new JsonResponse($jsonTimeSheet, Response::HTTP_OK, [], true);
    }

    #[Route('/api/time_sheet/assign/{id}', name: 'assign_time_sheet', methods: 'POST')]
    public function assignTimeSheet(Request $request, TimeSheet $timeSheet): JsonResponse
    {
        $user = $this->userService->getUserBy(json_decode($request->getContent(), true));
        $timeSheet->setUser($user);

        $this->timeSheetService->saveTimeSheet($timeSheet);

        $jsonTimeSheet = $this->serializer->serialize($timeSheet, 'json', ['groups' => 'getTimeSheet']);

        return new JsonResponse($jsonTimeSheet, Response::HTTP_OK, [], true);
    }

    #[Route('/api/time_sheet/match/{id}', name: 'match_time_sheet', methods: 'POST')]
    public function matchTimeSheet(TimeSheet $timeSheet)
    {
        $this->timeSheetService->matchTimeSheet($timeSheet);

        $json = $this->serializer->serialize($timeSheet, 'json', ['groups' => 'getTimeSheet']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }
}
