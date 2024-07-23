<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id_demande;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $description;

    /**
     * @var Client|null
     */
    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client", nullable: true)]
    private ?Client $client =null;

    /**
     * @var Admin|null
     */
    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: "id_admin", referencedColumnName: "id_admin", nullable: true)]
    private ?Admin $admin =null;

    /**
     * @var Coursiers|null
     */
    #[ORM\ManyToOne(targetEntity: Coursiers::class)]
    #[ORM\JoinColumn(name: "id_coursier", referencedColumnName: "id_coursier", nullable: true)]
    private ?Coursiers $coursier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_source = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_dest = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_livraison = null;

    public function __construct(
        ?\DateTimeInterface $date_demande = null,
        ?string $description = null
    ) {
        $this->date_demande = $date_demande;
        $this->description = $description;
    }



    public function getIdDemande(): ?int
    {
        return $this->id_demande;
    }

    public function setIdDemande(int $id_demande): self
    {
        $this->id_demande = $id_demande;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client|null $client
     */
    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @return Admin|null
     */
    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    /**
     * @param Admin|null $id_admin
     */
    public function setAdmin(?Admin $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return Coursiers|null
     */
    public function getCoursier(): ?Coursiers
    {
        return $this->coursier;
    }

    /**
     * @param Coursiers|null $coursier
     */
    public function setCoursier(?Coursiers $coursier): void
    {
        $this->coursier = $coursier;
    }

    public function getAdressSource(): ?string
    {
        return $this->adress_source;
    }

    public function setAdressSource(?string $adress_source): void
    {
        $this->adress_source = $adress_source;
    }

    public function getAdressDest(): ?string
    {
        return $this->adress_dest;
    }

    public function setAdressDest(?string $adress_dest): void
    {
        $this->adress_dest = $adress_dest;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): void
    {
        $this->poids = $poids;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(?\DateTimeInterface $date_demande): void
    {
        $this->date_demande = $date_demande;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(?\DateTimeInterface $date_livraison): void
    {
        $this->date_livraison = $date_livraison;
    }
}
