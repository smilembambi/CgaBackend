<?php

namespace App\Entity;

use App\Repository\EscaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EscaleRepository::class)
 */
class Escale
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
    private $aeroport;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups("apiExport")
     */
    private $indiceAeroport;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="escales")
     * @Groups("apiExport")
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Bagage::class, mappedBy="escaleDepart")
     */
    private $bagages;

    /**
     * @ORM\OneToMany(targetEntity=Vol::class, mappedBy="depart")
     */
    private $vols;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="escale")
     */
    private $users;

    public function __construct()
    {
        $this->bagages = new ArrayCollection();
        $this->vols = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getAeroport(): ?string
    {
        return $this->aeroport;
    }

    public function setAeroport(string $aeroport): self
    {
        $this->aeroport = $aeroport;

        return $this;
    }

    public function getIndiceAeroport(): ?string
    {
        return $this->indiceAeroport;
    }

    public function setIndiceAeroport(?string $indiceAeroport): self
    {
        $this->indiceAeroport = $indiceAeroport;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

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
            $bagage->setEscaleDepart($this);
        }

        return $this;
    }

    public function removeBagage(Bagage $bagage): self
    {
        if ($this->bagages->contains($bagage)) {
            $this->bagages->removeElement($bagage);
            // set the owning side to null (unless already changed)
            if ($bagage->getEscaleDepart() === $this) {
                $bagage->setEscaleDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vol[]
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): self
    {
        if (!$this->vols->contains($vol)) {
            $this->vols[] = $vol;
            $vol->setDepart($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): self
    {
        if ($this->vols->contains($vol)) {
            $this->vols->removeElement($vol);
            // set the owning side to null (unless already changed)
            if ($vol->getDepart() === $this) {
                $vol->setDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setEscale($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getEscale() === $this) {
                $user->setEscale(null);
            }
        }

        return $this;
    }
}
