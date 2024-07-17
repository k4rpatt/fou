<?php

namespace App\Entity;

use App\Repository\TrainRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainRepository::class)]
class Train
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $depart = null;

    #[ORM\ManyToOne(inversedBy: 'trains')]
    private ?Joueur $conducteur = null;

    #[ORM\ManyToOne(inversedBy: 'passagers')]
    private ?Joueur $passager1 = null;

    #[ORM\ManyToOne(inversedBy: 'passagers')]
    private ?Joueur $passager2 = null;

    #[ORM\ManyToOne(inversedBy: 'passagers')]
    private ?Joueur $passager3 = null;

    #[ORM\ManyToOne(inversedBy: 'passagers')]
    private ?Joueur $passager4 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepart(): ?\DateTimeInterface
    {
        return $this->depart;
    }

    public function setDepart(\DateTimeInterface $depart): static
    {
        $this->depart = $depart;

        return $this;
    }

    public function getConducteur(): ?Joueur
    {
        return $this->conducteur;
    }

    public function setConducteur(?Joueur $conducteur): static
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    public function getPassager1(): ?Joueur
    {
        return $this->passager1;
    }

    public function setPassager1(?Joueur $passager1): static
    {
        $this->passager1 = $passager1;

        return $this;
    }

    public function getPassager2(): ?Joueur
    {
        return $this->passager2;
    }

    public function setPassager2(?Joueur $passager2): static
    {
        $this->passager2 = $passager2;

        return $this;
    }

    public function getPassager3(): ?Joueur
    {
        return $this->passager3;
    }

    public function setPassager3(?Joueur $passager3): static
    {
        $this->passager3 = $passager3;

        return $this;
    }

    public function getPassager4(): ?Joueur
    {
        return $this->passager4;
    }

    public function setPassager4(?Joueur $passager4): static
    {
        $this->passager4 = $passager4;

        return $this;
    }
}
