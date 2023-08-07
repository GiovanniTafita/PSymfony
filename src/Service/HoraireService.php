<?php

namespace App\Service;

use App\Entity\Horaire;
use App\Repository\HoraireRepository;
use Doctrine\ORM\EntityManagerInterface;

class HoraireService
{
  public function __construct(
    private HoraireRepository $horaireRepository,
    private EntityManagerInterface $entityManager,
    private DateTimeService $dateTimeService
  ) {
  }

  public function saveHoraire(Horaire $horaire)
  {
    $this->entityManager->persist($horaire);
    $this->entityManager->flush();

    return $horaire;
  }

  public function start(Horaire $horaire)
  {
    if (!$horaire->getOutAt()) {
      $horaire->setInAt(new \DateTimeImmutable());
      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function end(Horaire $horaire)
  {
    if ($horaire->getInAt()) {
      $horaire->setOutAt(new \DateTimeImmutable());
      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function break(Horaire $horaire)
  {
    if ($horaire->getInAt()) {
      $horaire->setBreakAt(new \DateTimeImmutable());
      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function resume(Horaire $horaire)
  {
    if ($horaire->getBreakAt() && !$horaire->getOutAt()) {
      $now = new \DateTimeImmutable();
      $horaire->setResumeAt($now);
      $totalBreak = $this->dateTimeService->getSecondsDiff($horaire->getBreakAt(), $now);
      $horaire->setTotalBreak($totalBreak);

      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function totalTime(Horaire $horaire)
  {
    if ($horaire->getInAt() && $horaire->getOutAt()) {
      // En secondes
      $total = $this->dateTimeService->getSecondsDiff($horaire->getInAt(), $horaire->getOutAt());
      if ($horaire->getTotalBreak()) {
        $total = $total - $horaire->getTotalBreak();
      }
      // Si il y a pause
      $horaire->setTotalTime($total);
      $this->saveHoraire($horaire);
    }

    return $horaire;
  }
}
