<?php

namespace App\Entity;

use App\Repository\ConnexionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ConnexionRepository::class)
 */
class Connexion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiExport")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="connexions")
     * @Groups("apiExport")
     */
    private $utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="connexions")
     * @Groups("apiExport")
     */
    private $zone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateConnexion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDeconnexion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getDateConnexion(): ?\DateTimeInterface
    {
        return $this->dateConnexion;
    }

    public function setDateConnexion(\DateTimeInterface $dateConnexion): self
    {
        $this->dateConnexion = $dateConnexion;

        return $this;
    }

    public function getDateDeconnexion(): ?\DateTimeInterface
    {
        return $this->dateDeconnexion;
    }

    public function setDateDeconnexion(\DateTimeInterface $dateDeconnexion): self
    {
        $this->dateDeconnexion = $dateDeconnexion;

        return $this;
    }
}
