<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/api/home', name: 'app_home')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new secret controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }

    #[Route('api/timeZone', name: 'app_time_zone', methods: ['GET'])]
    public function timeZone()
    {
        $now = new DateTimeImmutable();
        return new JsonResponse(["time-zone" => $now->getTimezone()->getName()], Response::HTTP_OK);
    }
}
