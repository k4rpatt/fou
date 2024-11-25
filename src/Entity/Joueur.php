<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $niveau = null;

    #[ORM\Column(nullable: true)]
    private ?float $puissance = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_equipe1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_equipe2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_equipe3 = null;

    #[ORM\Column(nullable: true)]
    private ?float $PC_equipe4 = null;

    /**
     * @var Collection<int, Train>
     */
    #[ORM\OneToMany(targetEntity: Train::class, mappedBy: 'conducteur')]
    private Collection $trains;

    /**
     * @var Collection<int, Train>
     */
    #[ORM\OneToMany(targetEntity: Train::class, mappedBy: 'passager1')]
    private Collection $passagers;

    #[ORM\Column(nullable: true)]
    private ?float $Resistance = null;

    #[ORM\OneToOne(mappedBy: 'joueur', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Progression>
     */
    #[ORM\OneToMany(targetEntity: Progression::class, mappedBy: 'joueur', orphanRemoval: true)]
    private Collection $progressions;

    public function __construct()
    {
        $this->trains = new ArrayCollection();
        $this->passagers = new ArrayCollection();
        $this->progressions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getPuissance(): ?float
    {
        return $this->puissance;
    }

    public function setPuissance(?float $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getPCEquipe1(): ?float
    {
        return $this->PC_equipe1;
    }

    public function setPCEquipe1(?float $PC_equipe1): static
    {
        $this->PC_equipe1 = $PC_equipe1;

        return $this;
    }

    public function getPCEquipe2(): ?float
    {
        return $this->PC_equipe2;
    }

    public function setPCEquipe2(?float $PC_equipe2): static
    {
        $this->PC_equipe2 = $PC_equipe2;

        return $this;
    }

    public function getPCEquipe3(): ?float
    {
        return $this->PC_equipe3;
    }

    public function setPCEquipe3(?float $PC_equipe3): static
    {
        $this->PC_equipe3 = $PC_equipe3;

        return $this;
    }

    public function getPCEquipe4(): ?float
    {
        return $this->PC_equipe4;
    }

    public function setPCEquipe4(?float $PC_equipe4): static
    {
        $this->PC_equipe4 = $PC_equipe4;

        return $this;
    }

    /**
     * @return Collection<int, Train>
     */
    public function getTrains(): Collection
    {
        return $this->trains;
    }

    public function addTrain(Train $train): static
    {
        if (!$this->trains->contains($train)) {
            $this->trains->add($train);
            $train->setConducteur($this);
        }

        return $this;
    }

    public function removeTrain(Train $train): static
    {
        if ($this->trains->removeElement($train)) {
            // set the owning side to null (unless already changed)
            if ($train->getConducteur() === $this) {
                $train->setConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Train>
     */
    public function getPassagers(): Collection
    {
        return $this->passagers;
    }

    public function addPassager(Train $passager): static
    {
        if (!$this->passagers->contains($passager)) {
            $this->passagers->add($passager);
            $passager->setPassager1($this);
        }

        return $this;
    }

    public function removePassager(Train $passager): static
    {
        if ($this->passagers->removeElement($passager)) {
            // set the owning side to null (unless already changed)
            if ($passager->getPassager1() === $this) {
                $passager->setPassager1(null);
            }
        }

        return $this;
    }

    public function getResistance(): ?float
    {
        return $this->Resistance;
    }

    public function setResistance(float $Resistance): static
    {
        $this->Resistance = $Resistance;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setJoueur(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getJoueur() !== $this) {
            $user->setJoueur($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Progression>
     */
    public function getProgressions(): Collection
    {
        return $this->progressions;
    }

    public function getLastProgression()
    {
//        $this->getProgressions();
        return $this->progressions->last();
    }
    public function addProgression(Progression $progression): static
    {
        if (!$this->progressions->contains($progression)) {
            $this->progressions->add($progression);
            $progression->setJoueur($this);
        }

        return $this;
    }

    public function removeProgression(Progression $progression): static
    {
        if ($this->progressions->removeElement($progression)) {
            // set the owning side to null (unless already changed)
            if ($progression->getJoueur() === $this) {
                $progression->setJoueur(null);
            }
        }

        return $this;
    }
}
