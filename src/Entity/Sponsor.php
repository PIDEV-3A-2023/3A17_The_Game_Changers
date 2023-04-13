<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sponsor
 *
 * @ORM\Table(name="sponsor")
 * @ORM\Entity
 */
class Sponsor
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_sponsor", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_sponsor", type="string", length=300, nullable=false)
     */
    private $nomSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel_sponsor", type="string", length=12, nullable=false)
     */
    private $telSponsor;

    /**
     * @var string
     *
     * @ORM\Column(name="Email_sponsor", type="string", length=300, nullable=false)
     */
    private $emailSponsor;

    /**
     * @var float
     *
     * @ORM\Column(name="Montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    public function getIdSponsor(): ?int
    {
        return $this->idSponsor;
    }

    public function getNomSponsor(): ?string
    {
        return $this->nomSponsor;
    }

    public function setNomSponsor(string $nomSponsor): self
    {
        $this->nomSponsor = $nomSponsor;

        return $this;
    }

    public function getTelSponsor(): ?string
    {
        return $this->telSponsor;
    }

    public function setTelSponsor(string $telSponsor): self
    {
        $this->telSponsor = $telSponsor;

        return $this;
    }

    public function getEmailSponsor(): ?string
    {
        return $this->emailSponsor;
    }

    public function setEmailSponsor(string $emailSponsor): self
    {
        $this->emailSponsor = $emailSponsor;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }


}
