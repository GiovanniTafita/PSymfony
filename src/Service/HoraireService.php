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
}
