<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $vitesse_max = null;

    #[ORM\Column]
    private ?float $charge_maxsupp = null;

    #[ORM\Column(length: 255)]
    private ?string $auto_batterie = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $type_vehicule = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'vehicules', targetEntity: Reparation::class)]
    private Collection $reparations;

    #[ORM\ManyToOne(inversedBy: 'vehicule')]
    private ?Reparation $reparation = null;

    public function __construct()
    {
        $this->reparations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getVitesseMax(): ?string
    {
        return $this->vitesse_max;
    }

    public function setVitesseMax(string $vitesse_max): self
    {
        $this->vitesse_max = $vitesse_max;

        return $this;
    }

    public function getChargeMaxsupp(): ?float
    {
        return $this->charge_maxsupp;
    }

    public function setChargeMaxsupp(float $charge_maxsupp): self
    {
        $this->charge_maxsupp = $charge_maxsupp;

        return $this;
    }

    public function getAutoBatterie(): ?string
    {
        return $this->auto_batterie;
    }

    public function setAutoBatterie(string $auto_batterie): self
    {
        $this->auto_batterie = $auto_batterie;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getTypeVehicule(): ?string
    {
        return $this->type_vehicule;
    }

    public function setTypeVehicule(string $type_vehicule): self
    {
        $this->type_vehicule = $type_vehicule;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getReparation(): ?Reparation
    {
        return $this->reparation;
    }

    public function setReparation(?Reparation $reparation): self
    {
        $this->reparation = $reparation;

        return $this;
    }

}
