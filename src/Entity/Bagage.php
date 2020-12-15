<?php

namespace App\Entity;

use App\Repository\BagageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BagageRepository::class)
 */
class Bagage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("apiExport")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Passager::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $passager;

    /**
     * @ORM\ManyToOne(targetEntity=Escale::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $escaleDepart;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups("apiExport")
     */
    private $escaleTransit = [];

    /**
     * @ORM\ManyToOne(targetEntity=Escale::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $escaleArrive;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="no")
     * @Groups("apiExport")
     */
    private $agentChargementContenaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $agentChargementSoute;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $agentDechargementSoute;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $agentDechargementContenaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $agentLivraison;

    /**
     * @ORM\ManyToOne(targetEntity=Vol::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $vol;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("apiExport")
     */
    private $contenuBagage;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiExport")
     */
    private $estUploder;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateUpload;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateChargementContenaire;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateChargementSoute;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateDechargementSoute;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateDechargementContaire;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("apiExport")
     */
    private $dateLivraison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $codeBarre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $poids;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiExport")
     */
    private $estInconnu;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiExport")
     */
    private $estEnDetresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("apiExport")
     */
    private $origineDetresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $agentUploadManisfet;

    /**
     * @ORM\Column(type="boolean",  nullable=true)
     * @Groups("apiExport")
     */
    private $manifestCharge;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="bagages")
     * @Groups("apiExport")
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups("apiExport")
     */
    private $tagManuel;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassager(): ?Passager
    {
        return $this->passager;
    }

    public function setPassager(?Passager $passager): self
    {
        $this->passager = $passager;

        return $this;
    }

    public function getEscaleDepart(): ?Escale
    {
        return $this->escaleDepart;
    }

    public function setEscaleDepart(?Escale $escaleDepart): self
    {
        $this->escaleDepart = $escaleDepart;

        return $this;
    }

    public function getEscaleTransit(): ?array
    {
        return $this->escaleTransit;
    }

    public function setEscaleTransit(?array $escaleTransit): self
    {
        $this->escaleTransit = $escaleTransit;

        return $this;
    }

    public function getEscaleArrive(): ?Escale
    {
        return $this->escaleArrive;
    }

    public function setEscaleArrive(?Escale $escaleArrive): self
    {
        $this->escaleArrive = $escaleArrive;

        return $this;
    }

    public function getAgentChargementContenaire(): ?User
    {
        return $this->agentChargementContenaire;
    }

    public function setAgentChargementContenaire(?User $agentChargementContenaire): self
    {
        $this->agentChargementContenaire = $agentChargementContenaire;

        return $this;
    }

    public function getAgentChargementSoute(): ?User
    {
        return $this->agentChargementSoute;
    }

    public function setAgentChargementSoute(?User $agentChargementSoute): self
    {
        $this->agentChargementSoute = $agentChargementSoute;

        return $this;
    }

    public function getAgentDechargementSoute(): ?User
    {
        return $this->agentDechargementSoute;
    }

    public function setAgentDechargementSoute(?User $agentDechargementSoute): self
    {
        $this->agentDechargementSoute = $agentDechargementSoute;

        return $this;
    }

    public function getAgentDechargementContenaire(): ?User
    {
        return $this->agentDechargementContenaire;
    }

    public function setAgentDechargementContenaire(?User $agentDechargementContenaire): self
    {
        $this->agentDechargementContenaire = $agentDechargementContenaire;

        return $this;
    }

    public function getAgentLivraison(): ?User
    {
        return $this->agentLivraison;
    }

    public function setAgentLivraison(?User $agentLivraison): self
    {
        $this->agentLivraison = $agentLivraison;

        return $this;
    }

    public function getVol(): ?Vol
    {
        return $this->vol;
    }

    public function setVol(?Vol $vol): self
    {
        $this->vol = $vol;

        return $this;
    }

    public function getContenuBagage(): ?string
    {
        return $this->contenuBagage;
    }

    public function setContenuBagage(?string $contenuBagage): self
    {
        $this->contenuBagage = $contenuBagage;

        return $this;
    }

    public function getEstUploder(): ?bool
    {
        return $this->estUploder;
    }

    public function setEstUploder(?bool $estUploder): self
    {
        $this->estUploder = $estUploder;

        return $this;
    }

    public function getDateUpload(): ?\DateTimeInterface
    {
        return $this->dateUpload;
    }

    public function setDateUpload(?\DateTimeInterface $dateUpload): self
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    public function getDateChargementContenaire(): ?\DateTimeInterface
    {
        return $this->dateChargementContenaire;
    }

    public function setDateChargementContenaire(?\DateTimeInterface $dateChargementContenaire): self
    {
        $this->dateChargementContenaire = $dateChargementContenaire;

        return $this;
    }

    public function getDateChargementSoute(): ?\DateTimeInterface
    {
        return $this->dateChargementSoute;
    }

    public function setDateChargementSoute(?\DateTimeInterface $dateChargementSoute): self
    {
        $this->dateChargementSoute = $dateChargementSoute;

        return $this;
    }

    public function getDateDechargementSoute(): ?\DateTimeInterface
    {
        return $this->dateDechargementSoute;
    }

    public function setDateDechargementSoute(\DateTimeInterface $dateDechargementSoute): self
    {
        $this->dateDechargementSoute = $dateDechargementSoute;

        return $this;
    }

    public function getDateDechargementContaire(): ?\DateTimeInterface
    {
        return $this->dateDechargementContaire;
    }

    public function setDateDechargementContaire(?\DateTimeInterface $dateDechargementContaire): self
    {
        $this->dateDechargementContaire = $dateDechargementContaire;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getCodeBarre(): ?string
    {
        return $this->codeBarre;
    }

    public function setCodeBarre(string $codeBarre): self
    {
        $this->codeBarre = $codeBarre;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getEstInconnu(): ?bool
    {
        return $this->estInconnu;
    }

    public function setEstInconnu(?bool $estInconnu): self
    {
        $this->estInconnu = $estInconnu;

        return $this;
    }

    public function getEstEnDetresse(): ?bool
    {
        return $this->estEnDetresse;
    }

    public function setEstEnDetresse(?bool $estEnDetresse): self
    {
        $this->estEnDetresse = $estEnDetresse;

        return $this;
    }

    public function getOrigineDetresse(): ?string
    {
        return $this->origineDetresse;
    }

    public function setOrigineDetresse(?string $origineDetresse): self
    {
        $this->origineDetresse = $origineDetresse;

        return $this;
    }

    public function getAgentUploadManisfet(): ?User
    {
        return $this->agentUploadManisfet;
    }

    public function setAgentUploadManisfet(?User $agentUploadManisfet): self
    {
        $this->agentUploadManisfet = $agentUploadManisfet;

        return $this;
    }

    public function getManifestCharge(): ?bool
    {
        return $this->manifestCharge;
    }

    public function setManifestCharge(bool $manifestCharge): self
    {
        $this->manifestCharge = $manifestCharge;

        return $this;
    }

    public function getStatut(): ?Zone
    {
        return $this->statut;
    }

    public function setStatut(?Zone $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTagManuel(): ?bool
    {
        return $this->tagManuel;
    }

    public function setTagManuel(?bool $tagManuel): self
    {
        $this->tagManuel = $tagManuel;

        return $this;
    }


}
