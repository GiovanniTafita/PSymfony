<?php

namespace App\Service;

use App\Entity\Horaire;
use App\Entity\TimeSheet;
use App\Repository\TimeSheetRepository;
use Doctrine\ORM\EntityManagerInterface;

class TimeSheetService
{
  public function __construct(
    private TimeSheetRepository $timeSheetRepository,
    private EntityManagerInterface $entityManager
  ) {
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
    if (!$startDate || !$endDate || $timeSheet->getHoraires()->count() > 0) {
      return $timeSheet;
    } else {
      $horaires = [];
      for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate = $currentDate->modify('+1 day')) {
        $newHoraire = new Horaire();
        $newHoraire->setDate($currentDate->format('Y-m-d'));

        if ($this->isWeekEnd($currentDate)) {
          $newHoraire->setTodo(8);
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

  public function isWeekEnd($date)
  {
    $dayOfWeek = $date->format('N');
    if ($dayOfWeek == 6 || $dayOfWeek == 7) {
      return true;
    }
    return false;
  }
}
