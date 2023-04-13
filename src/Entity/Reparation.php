<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reparation
 *
 * @ORM\Table(name="reparation", indexes={@ORM\Index(name="fk_vehc", columns={"id_v"}), @ORM\Index(name="fk_emp", columns={"id_employe"})})
 * @ORM\Entity
 */
class Reparation
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
     * @var int|null
     *
     * @ORM\Column(name="id_v", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idV = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description_panne", type="string", length=80, nullable=true, options={"default"="NULL"})
     */
    private $descriptionPanne = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Etat", type="string", length=20, nullable=true, options={"default"="NULL"})
     */
    private $etat = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_rep", type="date", nullable=false)
     */
    private $dateRep;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_manu", type="date", nullable=false)
     */
    private $dateManu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_defect", type="date", nullable=false)
     */
    private $dateDefect;

    /**
     * @var \Employee
     *
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_employe", referencedColumnName="id")
     * })
     */
    private $idEmploye;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdV(): ?int
    {
        return $this->idV;
    }

    public function setIdV(?int $idV): self
    {
        $this->idV = $idV;

        return $this;
    }

    public function getDescriptionPanne(): ?string
    {
        return $this->descriptionPanne;
    }

    public function setDescriptionPanne(?string $descriptionPanne): self
    {
        $this->descriptionPanne = $descriptionPanne;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->dateRep;
    }

    public function setDateRep(\DateTimeInterface $dateRep): self
    {
        $this->dateRep = $dateRep;

        return $this;
    }

    public function getDateManu(): ?\DateTimeInterface
    {
        return $this->dateManu;
    }

    public function setDateManu(\DateTimeInterface $dateManu): self
    {
        $this->dateManu = $dateManu;

        return $this;
    }

    public function getDateDefect(): ?\DateTimeInterface
    {
        return $this->dateDefect;
    }

    public function setDateDefect(\DateTimeInterface $dateDefect): self
    {
        $this->dateDefect = $dateDefect;

        return $this;
    }

    public function getIdEmploye(): ?Employee
    {
        return $this->idEmploye;
    }

    public function setIdEmploye(?Employee $idEmploye): self
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }


}
