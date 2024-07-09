<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionRepository::class)]
class Position
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $posX = null;

    #[ORM\Column]
    private ?float $posY = null;

    #[ORM\ManyToOne(inversedBy: 'positions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Alliance $alliance = null;

    #[ORM\ManyToOne(inversedBy: 'positions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serveur $serveur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cible = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosX(): ?float
    {
        return round($this->posX);
    }

    public function setPosX(float $posX): static
    {
        $this->posX = $posX;

        return $this;
    }

    public function getPosY(): ?float
    {
        return round($this->posY);
    }

    public function setPosY(float $posY): static
    {
        $this->posY = $posY;

        return $this;
    }

    public function getAlliance(): ?Alliance
    {
        return $this->alliance;
    }

    public function setAlliance(?Alliance $alliance): static
    {
        $this->alliance = $alliance;

        return $this;
    }

    public function getServeur(): ?Serveur
    {
        return $this->serveur;
    }

    public function setServeur(?Serveur $serveur): static
    {
        $this->serveur = $serveur;

        return $this;
    }

    public function __toString()
    {
        return "(".$this->getPosX().";".$this->getPosY().")";
    }

    public function getCible(): ?string
    {
        return $this->cible;
    }

    public function setCible(?string $cible): static
    {
        $this->cible = $cible;

        return $this;
    }
}
