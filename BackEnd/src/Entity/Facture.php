<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id_facture;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $montant;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $status;

    /**
     * @var Client|null
     */
    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client", nullable: true)]
    private ?Client $client;

    public function __construct(?float $montant, ?string $status)
    {
        $this->montant = $montant;
        $this->status = $status;
    }

    // Getters and setters...

    public function getIdFacture(): ?int
    {
        return $this->id_facture;
    }

    public function setIdFacture(int $id_facture): static
    {
        $this->id_facture = $id_facture;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(?float $montant): static
    {
        $this->montant = $montant;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
