<?php

namespace App\Entity;

use App\Repository\ProgressionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgressionRepository::class)]
class Progression
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'progressions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Joueur $joueur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateProgression = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_tank = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_avion = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_missile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): static
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getDateProgression(): ?\DateTimeInterface
    {
        return $this->dateProgression;
    }

    public function setDateProgression(\DateTimeInterface $dateProgression): static
    {
        $this->dateProgression = $dateProgression;

        return $this;
    }

    public function getPCTank(): ?float
    {
        return $this->PC_tank;
    }

    public function setPCTank(?float $PC_tank): static
    {
        $this->PC_tank = $PC_tank;

        return $this;
    }

    public function getPCAvion(): ?float
    {
        return $this->PC_avion;
    }

    public function setPCAvion(?float $PC_avion): static
    {
        $this->PC_avion = $PC_avion;

        return $this;
    }

    public function getPCMissile(): ?float
    {
        return $this->PC_missile;
    }

    public function setPCMissile(?float $PC_missile): static
    {
        $this->PC_missile = $PC_missile;

        return $this;
    }
}
