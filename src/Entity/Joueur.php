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
}
