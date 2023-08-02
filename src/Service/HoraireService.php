<?php

namespace App\Service;

use App\Entity\Horaire;
use App\Entity\TimeSheet;
use App\Repository\HoraireRepository;
use Doctrine\ORM\EntityManagerInterface;

class HoraireService
{
  public function __construct(
    private HoraireRepository $horaireRepository,
    private EntityManagerInterface $entityManager
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
      $totalBreak = $this->getSecondesDiff($horaire->getBreakAt(), $now);
      $horaire->setTotalBreak($totalBreak);

      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function totalTime(Horaire $horaire)
  {
    if ($horaire->getInAt() && $horaire->getOutAt()) {
      // En secondes
      $total = $this->getSecondesDiff($horaire->getInAt(), $horaire->getOutAt());
      if ($horaire->getTotalBreak()) {
        $total = $total - $horaire->getTotalBreak();
      }
      // Si il y a pause
      $horaire->setTotalTime($total);
      $this->saveHoraire($horaire);
    }

    return $horaire;
  }

  public function getMinutesDiff(\DateTimeImmutable $start, \DateTimeImmutable $end)
  {
    $interval = $start->diff($end);
    // En Minutes
    return $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
  }

  public function getSecondesDiff(\DateTimeImmutable $start, \DateTimeImmutable $end)
  {
    $interval = $start->diff($end);
    // En Secondes
    return $interval->s + ($interval->i * 60) + ($interval->h * 3600) + ($interval->days * 86400);
  }
}
