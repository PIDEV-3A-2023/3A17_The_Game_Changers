<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehicule
 *
 * @ORM\Table(name="vehicule")
 * @ORM\Entity
 */
class Vehicule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=30, nullable=false)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="vitesse_max", type="string", length=30, nullable=false)
     */
    private $vitesseMax;

    /**
     * @var float
     *
     * @ORM\Column(name="charge_maxsupp", type="float", precision=10, scale=0, nullable=false)
     */
    private $chargeMaxsupp;

    /**
     * @var string
     *
     * @ORM\Column(name="auto_batterie", type="string", length=50, nullable=false)
     */
    private $autoBatterie;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=30, nullable=false)
     */
    private $couleur;

    /**
     * @var string
     *
     * @ORM\Column(name="type_vehicule", type="string", length=30, nullable=false)
     */
    private $typeVehicule;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

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
        return $this->vitesseMax;
    }

    public function setVitesseMax(string $vitesseMax): self
    {
        $this->vitesseMax = $vitesseMax;

        return $this;
    }

    public function getChargeMaxsupp(): ?float
    {
        return $this->chargeMaxsupp;
    }

    public function setChargeMaxsupp(float $chargeMaxsupp): self
    {
        $this->chargeMaxsupp = $chargeMaxsupp;

        return $this;
    }

    public function getAutoBatterie(): ?string
    {
        return $this->autoBatterie;
    }

    public function setAutoBatterie(string $autoBatterie): self
    {
        $this->autoBatterie = $autoBatterie;

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
        return $this->typeVehicule;
    }

    public function setTypeVehicule(string $typeVehicule): self
    {
        $this->typeVehicule = $typeVehicule;

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


}
