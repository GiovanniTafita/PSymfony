<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getTimeSheet', 'getHoraire'])]
    private ?int $id = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $date = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $inAt = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $outAt = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $breakAt = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(nullable: true)]
    private ?int $totalTime = null;

    #[Groups(['getTimeSheet', 'getHoraire'])]
    #[ORM\Column(nullable: true)]
    private ?int $totalBreak = null;

    #[ORM\ManyToOne(inversedBy: 'horaires')]
    private ?TimeSheet $timeSheet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getInAt(): ?\DateTimeImmutable
    {
        return $this->inAt;
    }

    public function setInAt(?\DateTimeImmutable $inAt): static
    {
        $this->inAt = $inAt;

        return $this;
    }

    public function getOutAt(): ?\DateTimeImmutable
    {
        return $this->outAt;
    }

    public function setOutAt(?\DateTimeImmutable $outAt): static
    {
        $this->outAt = $outAt;

        return $this;
    }

    public function getBreakAt(): ?\DateTimeImmutable
    {
        return $this->breakAt;
    }

    public function setBreakAt(?\DateTimeImmutable $breakAt): static
    {
        $this->breakAt = $breakAt;

        return $this;
    }

    public function getTotalTime(): ?int
    {
        return $this->totalTime;
    }

    public function setTotalTime(?int $totalTime): static
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    public function getTotalBreak(): ?int
    {
        return $this->totalBreak;
    }

    public function setTotalBreak(?int $totalBreak): static
    {
        $this->totalBreak = $totalBreak;

        return $this;
    }

    public function getTimeSheet(): ?TimeSheet
    {
        return $this->timeSheet;
    }

    public function setTimeSheet(?TimeSheet $timeSheet): static
    {
        $this->timeSheet = $timeSheet;

        return $this;
    }
}
