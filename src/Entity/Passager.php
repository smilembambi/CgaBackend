<?php

namespace App\Entity;

use App\Repository\PassagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PassagerRepository::class)
 */
class Passager
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiExport")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups("apiExport")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("apiExport")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $nationalite;

    /**
     * @ORM\OneToMany(targetEntity=Bagage::class, mappedBy="passager")
     */
    private $bagages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $siege;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $ticket;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $pnr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("apiExport")
     */
    private $franchiseDemande;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("apiExport")
     */
    private $franchiseDisponible;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("apiExport")
     */
    private $excedant;

    public function __construct()
    {
        $this->bagages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * @return Collection|Bagage[]
     */
    public function getBagages(): Collection
    {
        return $this->bagages;
    }

    public function addBagage(Bagage $bagage): self
    {
        if (!$this->bagages->contains($bagage)) {
            $this->bagages[] = $bagage;
            $bagage->setPassager($this);
        }

        return $this;
    }

    public function removeBagage(Bagage $bagage): self
    {
        if ($this->bagages->contains($bagage)) {
            $this->bagages->removeElement($bagage);
            // set the owning side to null (unless already changed)
            if ($bagage->getPassager() === $this) {
                $bagage->setPassager(null);
            }
        }

        return $this;
    }

    public function getSiege(): ?string
    {
        return $this->siege;
    }

    public function setSiege(?string $siege): self
    {
        $this->siege = $siege;

        return $this;
    }

    public function getTicket(): ?string
    {
        return $this->ticket;
    }

    public function setTicket(?string $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getPnr(): ?string
    {
        return $this->pnr;
    }

    public function setPnr(?string $pnr): self
    {
        $this->pnr = $pnr;

        return $this;
    }

    public function getFranchiseDemande(): ?int
    {
        return $this->franchiseDemande;
    }

    public function setFranchiseDemande(?int $franchiseDemande): self
    {
        $this->franchiseDemande = $franchiseDemande;

        return $this;
    }

    public function getFranchiseDisponible(): ?int
    {
        return $this->franchiseDisponible;
    }

    public function setFranchiseDisponible(?int $franchiseDisponible): self
    {
        $this->franchiseDisponible = $franchiseDisponible;

        return $this;
    }

    public function getExcedant(): ?int
    {
        return $this->excedant;
    }

    public function setExcedant(int $excedant): self
    {
        $this->excedant = $excedant;

        return $this;
    }
}
