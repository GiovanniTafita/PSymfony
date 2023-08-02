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
    $horaire->setInAt(new \DateTimeImmutable());
    $this->saveHoraire($horaire);

    return $horaire;
  }

  public function end(Horaire $horaire)
  {
    $horaire->setOutAt(new \DateTimeImmutable());
    $this->saveHoraire($horaire);

    return $horaire;
  }

  public function totalTime(Horaire $horaire)
  {
    if (!$horaire->getInAt() || !$horaire->getOutAt()) {
      return $horaire;
    } else {
      $interval = $horaire->getInAt()->diff($horaire->getOutAt());

      // En Minutes
      $total = $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
      $horaire->setTotalTime($total);

      $this->saveHoraire($horaire);

      return $horaire;
    }
  }
}
