<?php

namespace App\Entity;

use App\Repository\ServeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServeurRepository::class)]
class Serveur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    /**
     * @var Collection<int, Alliance>
     */
    #[ORM\OneToMany(targetEntity: Alliance::class, mappedBy: 'serveur', orphanRemoval: true)]
    private Collection $alliances;

    /**
     * @var Collection<int, Position>
     */
    #[ORM\OneToMany(targetEntity: Position::class, mappedBy: 'serveur', orphanRemoval: true)]
    private Collection $positions;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $debut = null;



    public function __toString()
    {
        return $this->getNumero()."";
    }
    public function __construct()
    {
        $this->alliances = new ArrayCollection();
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection<int, Alliance>
     */
    public function getAlliances(): Collection
    {
        return $this->alliances;
    }

    public function addAlliance(Alliance $alliance): static
    {
        if (!$this->alliances->contains($alliance)) {
            $this->alliances->add($alliance);
            $alliance->setServeur($this);
        }

        return $this;
    }

    public function removeAlliance(Alliance $alliance): static
    {
        if ($this->alliances->removeElement($alliance)) {
            // set the owning side to null (unless already changed)
            if ($alliance->getServeur() === $this) {
                $alliance->setServeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): static
    {
        if (!$this->positions->contains($position)) {
            $this->positions->add($position);
            $position->setServeur($this);
        }

        return $this;
    }

    public function removePosition(Position $position): static
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getServeur() === $this) {
                $position->setServeur(null);
            }
        }

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
//        if (!$this->getDebut()) $this->debut = new \DateTime();
        $this->debut->add(\DateInterval::createFromDateString('4 hours'));
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }
}
