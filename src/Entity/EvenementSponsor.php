<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EvenementSponsor
 *
 * @ORM\Table(name="evenement_sponsor", indexes={@ORM\Index(name="FK_EVENT", columns={"ID_event"}), @ORM\Index(name="FK_SPONSOR", columns={"ID_sponsor"})})
 * @ORM\Entity
 */
class EvenementSponsor
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Sponsor
     *
     * @ORM\ManyToOne(targetEntity="Sponsor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_sponsor", referencedColumnName="ID_sponsor")
     * })
     */
    private $idSponsor;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ID_event", referencedColumnName="ID_event")
     * })
     */
    private $idEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSponsor(): ?Sponsor
    {
        return $this->idSponsor;
    }

    public function setIdSponsor(?Sponsor $idSponsor): self
    {
        $this->idSponsor = $idSponsor;

        return $this;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evenement $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }


}
