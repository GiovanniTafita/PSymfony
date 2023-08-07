<?php

namespace App\Entity;

use App\Repository\TimeSheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TimeSheetRepository::class)]
class TimeSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('getTimeSheet')]
    private ?int $id = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startAt = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endAt = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?int $dailyHours = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?bool $weekend = null;

    #[Groups('getTimeSheet')]
    #[ORM\ManyToOne(inversedBy: 'timeSheets', cascade: ["persist"])]
    private ?User $user = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uptatedAt = null;

    #[Groups('getTimeSheet')]
    #[ORM\OneToMany(mappedBy: 'timeSheet', targetEntity: Horaire::class)]
    private Collection $horaires;

    #[Groups('getTimeSheet')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDailyHours(): ?int
    {
        return $this->dailyHours;
    }

    public function setDailyHours(?int $dailyHours): static
    {
        $this->dailyHours = $dailyHours;

        return $this;
    }

    public function isWeekend(): ?bool
    {
        return $this->weekend;
    }

    public function setWeekend(?bool $weekend): static
    {
        $this->weekend = $weekend;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUptatedAt(): ?\DateTimeImmutable
    {
        return $this->uptatedAt;
    }

    public function setUptatedAt(?\DateTimeImmutable $UptatedAt): static
    {
        $this->uptatedAt = $UptatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Horaire>
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaire $horaire): static
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires->add($horaire);
            $horaire->setTimeSheet($this);
        }

        return $this;
    }

    public function removeHoraire(Horaire $horaire): static
    {
        if ($this->horaires->removeElement($horaire)) {
            // set the owning side to null (unless already changed)
            if ($horaire->getTimeSheet() === $this) {
                $horaire->setTimeSheet(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
