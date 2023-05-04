<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="Rating", indexes={@ORM\Index(name="CLE_ETRANGERE", columns={"id_suivi"})})
 * @ORM\Entity
 */
class Rating {

    /**
     * @var int
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private ?int $note;

    /**
     * @var \SuiviReclamation
     *
     * @ORM\ManyToOne(targetEntity="SuiviReclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_suivi", referencedColumnName="id")
     * })
     */
    private $idSuivi;
    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer", nullable=false)
     */
    private ?int $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSuivi(): ?SuiviReclamation
    {
        return $this->idSuivi;
    }

    public function setIdSuivi(?SuiviReclamation $suiv): self
    {
        $this->idSuivi = $suiv;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(?int $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function getNote(): ?int
    {
        return $this->note;
    }
    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

}