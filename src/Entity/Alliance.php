<?php

namespace App\Entity;

use App\Repository\AllianceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AllianceRepository::class)]
class Alliance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\ManyToOne(inversedBy: 'alliances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serveur $serveur = null;

    /**
     * @var Collection<int, Position>
     */
    #[ORM\OneToMany(targetEntity: Position::class, mappedBy: 'alliance')]
    private Collection $positions;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zone = null;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return "".$this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function getGDColor(\GdImage $gdImage)
    {
        $r = hexdec(substr($this->getCouleur(), 1,2));
        $g = hexdec(substr($this->getCouleur(), 3,2));
        $b = hexdec(substr($this->getCouleur(), 5,2));
//        return imagecolorallocate($gdImage, 255, 0, 0);
        return imagecolorallocate($gdImage, $r, $g, $b);
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

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
            $position->setAlliance($this);
        }

        return $this;
    }

    public function removePosition(Position $position): static
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getAlliance() === $this) {
                $position->setAlliance(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return "#".$this->getServeur()."-".$this->getNom();
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }
}
