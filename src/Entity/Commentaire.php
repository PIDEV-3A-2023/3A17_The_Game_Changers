<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_com", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCom;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255, nullable=false)
     */
    private $contenu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_c", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $idC = NULL;

    public function getIdCom(): ?int
    {
        return $this->idCom;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getIdC(): ?int
    {
        return $this->idC;
    }

    public function setIdC(?int $idC): self
    {
        $this->idC = $idC;

        return $this;
    }


}
