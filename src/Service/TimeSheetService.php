<?php

namespace App\Service;

use App\Entity\Horaire;
use App\Entity\TimeSheet;
use App\Repository\TimeSheetRepository;
use Doctrine\ORM\EntityManagerInterface;

class TimeSheetService
{
  public function __construct(
    private TimeSheetRepository    $timeSheetRepository,
    private EntityManagerInterface $entityManager,
    private DateTimeService        $dateTimeService
  )
  {
  }

  public function getTimeSheets()
  {
    return $this->timeSheetRepository->findAll();
  }

  public function getTimeSheet($id)
  {
  }

  public function saveTimeSheet(TimeSheet $timeSheet)
  {
    if (!$timeSheet->getCreatedAt()) {
      $timeSheet->setCreatedAt(new \DateTimeImmutable());
    } else {
      $timeSheet->setUptatedAt(new \DateTimeImmutable());
    }

    $this->entityManager->persist($timeSheet);
    $this->entityManager->flush();

    return $timeSheet;
  }

  public function deleteTimeSheet(TimeSheet $timeSheet)
  {
    $timeSheet->setDeletedAt(new \DateTimeImmutable());
    $this->saveTimeSheet($timeSheet);
  }

  public function matchTimeSheet(TimeSheet $timeSheet)
  {
    $startDate = $timeSheet->getStartAt();
    $endDate = $timeSheet->getEndAt();
    $todo = $timeSheet->getDailyHours() ?: 8;
    if (!$startDate || !$endDate || $timeSheet->getHoraires()->count() > 0) {
      return $timeSheet;
    } else {
      $horaires = [];
      for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = $currentDate->modify('+1 day')) {
        $newHoraire = new Horaire();
        $newHoraire->setDate($currentDate->format('Y-m-d'));

        if (!$this->dateTimeService->isWeekEnd($currentDate)) {
          $todoSeconds = $this->dateTimeService->convertToSeconds($todo);
          $newHoraire->setTodo($todoSeconds);
        }

        $this->entityManager->persist($newHoraire);

        $timeSheet->addHoraire($newHoraire);

        array_push($horaires, $newHoraire);
      }
      $this->entityManager->persist($timeSheet);
      $this->entityManager->flush();

      return $timeSheet;
    }
  }
}
