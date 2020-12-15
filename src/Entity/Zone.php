<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ZoneRepository::class)
 */
class Zone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiExport")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("apiExport")
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Connexion::class, mappedBy="zone")
     */
    private $connexions;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups("apiExport")
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("apiExport")
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity=Bagage::class, mappedBy="statut")
     */
    private $bagages;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("apiExport")
     */
    private $scanZone;

    public function __construct()
    {
        $this->connexions = new ArrayCollection();
        $this->bagages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Connexion[]
     */
    public function getConnexions(): Collection
    {
        return $this->connexions;
    }

    public function addConnexion(Connexion $connexion): self
    {
        if (!$this->connexions->contains($connexion)) {
            $this->connexions[] = $connexion;
            $connexion->setZone($this);
        }

        return $this;
    }

    public function removeConnexion(Connexion $connexion): self
    {
        if ($this->connexions->contains($connexion)) {
            $this->connexions->removeElement($connexion);
            // set the owning side to null (unless already changed)
            if ($connexion->getZone() === $this) {
                $connexion->setZone(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
            $bagage->setStatut($this);
        }

        return $this;
    }

    public function removeBagage(Bagage $bagage): self
    {
        if ($this->bagages->contains($bagage)) {
            $this->bagages->removeElement($bagage);
            // set the owning side to null (unless already changed)
            if ($bagage->getStatut() === $this) {
                $bagage->setStatut(null);
            }
        }

        return $this;
    }

    public function getScanZone(): ?bool
    {
        return $this->scanZone;
    }

    public function setScanZone(bool $scanZone): self
    {
        $this->scanZone = $scanZone;

        return $this;
    }
}
