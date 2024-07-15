<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_source = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_dest = null;

    #[ORM\Column(nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_livraison = null;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="demandes")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private ?Client $client = null;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="demandes")
     * @ORM\JoinColumn(name="id_admin", referencedColumnName="id_admin")
     */
    private ?Admin $id_admin = null;

    public function __construct(
        ?string $adress_source,
        ?string $adress_dest,
        ?float $poids,
        ?\DateTimeInterface $date_demande,
        ?string $status,
        ?\DateTimeInterface $date_livraison
    ) {
        $this->adress_source = $adress_source;
        $this->adress_dest = $adress_dest;
        $this->poids = $poids;
        $this->date_demande = $date_demande;
        $this->status = $status;
        $this->date_livraison = $date_livraison;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Coursiers::class, inversedBy="demandes")
     * @ORM\JoinColumn(name="id_coursier", referencedColumnName="id_coursier")
     */
    private ?Coursiers $coursier = null;

    public function getIdDemande(): ?int
    {
        return $this->id_demande;
    }

    public function setIdDemande(int $id_demande): static
    {
        $this->id_demande = $id_demande;

        return $this;
    }

    public function getAdressSource(): ?string
    {
        return $this->adress_source;
    }

    public function setAdressSource(?string $adress_source): static
    {
        $this->adress_source = $adress_source;

        return $this;
    }

    public function getAdressDest(): ?string
    {
        return $this->adress_dest;
    }

    public function setAdressDest(?string $adress_dest): static
    {
        $this->adress_dest = $adress_dest;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(?\DateTimeInterface $date_demande): static
    {
        $this->date_demande = $date_demande;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->date_livraison;
    }

    public function setDateLivraison(?\DateTimeInterface $date_livraison): static
    {
        $this->date_livraison = $date_livraison;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getIdAdmin(): ?Admin
    {
        return $this->id_admin;
    }

    public function setIdAdmin(?Admin $id_admin): static
    {
        $this->id_admin = $id_admin;

        return $this;
    }

    public function getCoursier(): ?Coursiers
    {
        return $this->coursier;
    }

    public function setCoursier(?Coursiers $coursier): static
    {
        $this->coursier = $coursier;

        return $this;
    }
}
