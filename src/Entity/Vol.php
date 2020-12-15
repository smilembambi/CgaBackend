<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VolRepository::class)
 */
class Vol
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
    private $numeroVol;



    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups("apiExport")
     */
    private $escale = [];

    /**
     * @ORM\ManyToOne(targetEntity=Escale::class, inversedBy="vols")
     * @Groups("apiExport")
     */
    private $depart;

    /**
     * @ORM\ManyToOne(targetEntity=Escale::class, inversedBy="vols")
     * @Groups("apiExport")
     */
    private $arrive;

    /**
     * @ORM\OneToMany(targetEntity=Bagage::class, mappedBy="vol")
     */
    private $bagages;

    public function __construct()
    {
        $this->bagages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroVol(): ?string
    {
        return $this->numeroVol;
    }

    public function setNumeroVol(string $numeroVol): self
    {
        $this->numeroVol = $numeroVol;

        return $this;
    }



    public function getEscale(): ?array
    {
        return $this->escale;
    }

    public function getDepart(): ?Escale
    {
        return $this->depart;
    }

    public function setDepart(?Escale $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function getArrive(): ?Escale
    {
        return $this->arrive;
    }

    public function setArrive(?Escale $arrive): self
    {
        $this->arrive = $arrive;

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
            $bagage->setVol($this);
        }

        return $this;
    }

    public function removeBagage(Bagage $bagage): self
    {
        if ($this->bagages->contains($bagage)) {
            $this->bagages->removeElement($bagage);
            // set the owning side to null (unless already changed)
            if ($bagage->getVol() === $this) {
                $bagage->setVol(null);
            }
        }

        return $this;
    }



}
