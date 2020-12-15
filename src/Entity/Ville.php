<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("apiExport")
     */
    private $pays;

    /**
     * @ORM\OneToMany(targetEntity=Escale::class, mappedBy="ville")
     */
    private $escales;

    public function __construct()
    {
        $this->escales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection|Escale[]
     */
    public function getEscales(): Collection
    {
        return $this->escales;
    }

    public function addEscale(Escale $escale): self
    {
        if (!$this->escales->contains($escale)) {
            $this->escales[] = $escale;
            $escale->setVille($this);
        }

        return $this;
    }

    public function removeEscale(Escale $escale): self
    {
        if ($this->escales->contains($escale)) {
            $this->escales->removeElement($escale);
            // set the owning side to null (unless already changed)
            if ($escale->getVille() === $this) {
                $escale->setVille(null);
            }
        }

        return $this;
    }
}
