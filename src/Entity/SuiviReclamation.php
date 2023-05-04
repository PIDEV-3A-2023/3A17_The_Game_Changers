<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviReclamation
 *
 * @ORM\Table(name="suivi_reclamation", indexes={@ORM\Index(name="CLE_ETRANGERE", columns={"id_reclamation"})})
 * @ORM\Entity
 */
class SuiviReclamation
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
     * @var string|null
     *
     * @ORM\Column(name="etat_reclamation", type="string", length=255, nullable=true)
     */
    
    private $reponseReclamation;

    /**
     * @var \Reclamation
     *
     * @ORM\ManyToOne(targetEntity="Reclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reclamation", referencedColumnName="Id")
     * })
     */
    private $idReclamation;
    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getReponseReclamation(): ?string
    {
        return $this->reponseReclamation;
    }

    public function setReponseReclamation(?string $reponseReclamation): self
    {
        $this->reponseReclamation = $reponseReclamation;

        return $this;
    }

    public function getIdReclamation(): ?Reclamation
    {
        return $this->idReclamation;
    }

    public function setIdReclamation(?Reclamation $idReclamation): self
    {
        $this->idReclamation = $idReclamation;

        return $this;
    }


}


