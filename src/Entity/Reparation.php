<?php

namespace App\Entity;

use App\Repository\ReparationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection ;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ReparationRepository::class)]
class Reparation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez remplir ce champ")]
    private ?string $Description_Panne = null;
    #[ORM\OneToMany(mappedBy: 'id_employe', targetEntity: Employee::class,)]
    private Collection $employees;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_declaration;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_rep = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_recuperation = null;

    #[ORM\OneToMany(mappedBy: 'reparation', targetEntity: Vehicule::class)]
    private Collection $vehicule;

    #[ORM\Column(type: 'integer')]
    private ?int $id_veh = null;

    public function getIdVeh(): ?int
    {
        return $this->id_veh;
    }

    public function setIdVeh(?int $id_veh): self
    {
        $this->id_veh = $id_veh;

        return $this;
    }

    public function __construct()
    {
        $this->vehicule = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionPanne(): ?string
    {
        return $this->Description_Panne;
    }

    public function setDescriptionPanne(?string $Description_Panne): self
    {
        $this->Description_Panne = $Description_Panne;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat ? 'reparé' : 'non reparé';
    }

    public function setEtat(?string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->Date_rep;
    }

    public function setDateRep(?\DateTimeInterface $Date_rep): self
    {
        $this->Date_rep = $Date_rep;

        return $this;
    }

    public function getDateDeclaration(): ?\DateTimeInterface
    {
        return $this->Date_declaration;
    }

    public function setDateDeclaration(?\DateTimeInterface $Date_declaration): self
    {
        $this->Date_declaration = $Date_declaration;

        return $this;
    }

    public function getDateRecuperation(): ?\DateTimeInterface
    {
        return $this->Date_recuperation;
    }

    public function setDateRecuperation(?\DateTimeInterface $Date_recuperation): self
    {
        $this->Date_recuperation = $Date_recuperation;


        return $this;
    }

    /**
     * @return Collection
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * @param Collection $employees
     */
    public function setEmployees(Collection $employees): void
    {
        $this->employees = $employees;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehicule(): Collection
    {
        return $this->vehicule;
    }

    public function addVehicule(Vehicule $vehicule): self
    {
        if (!$this->vehicule->contains($vehicule)) {
            $this->vehicule->add($vehicule);
            $vehicule->setReparation($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): self
    {
        if ($this->vehicule->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getReparation() === $this) {
                $vehicule->setReparation(null);
            }
        }

        return $this;
    }






}
