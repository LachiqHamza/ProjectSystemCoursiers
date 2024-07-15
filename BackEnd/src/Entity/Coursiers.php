<?php

namespace App\Entity;

use App\Repository\CoursiersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursiersRepository::class)]
class Coursiers
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id_coursier;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nom;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $prenom;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $tele;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $email;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $password;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $role;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $Cin;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $Date_intergration;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $salaire;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(mappedBy: 'id_admin', targetEntity: Demande::class)]
    private Collection $demandes;

    public function __construct(
        ?string $nom = null,
        ?string $prenom = null,
        ?string $tele = null,
        ?string $email = null,
        ?string $password = null,
        ?string $role = null,
        ?string $Cin = null,
        ?\DateTimeInterface $Date_intergration = null,
        ?float $salaire = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->tele = $tele;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->Cin = $Cin;
        $this->Date_intergration = $Date_intergration;
        $this->salaire = $salaire;
        $this->demandes = new ArrayCollection();
    }

    // Getters and setters...

    public function getIdCoursier(): ?int
    {
        return $this->id_coursier;
    }

    public function setIdCoursier(int $id_coursier): static
    {
        $this->id_coursier = $id_coursier;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(?string $tele): static
    {
        $this->tele = $tele;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->Cin;
    }

    public function setCin(?string $Cin): static
    {
        $this->Cin = $Cin;

        return $this;
    }

    public function getDateIntergration(): ?\DateTimeInterface
    {
        return $this->Date_intergration;
    }

    public function setDateIntergration(?\DateTimeInterface $Date_intergration): static
    {
        $this->Date_intergration = $Date_intergration;

        return $this;
    }

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): static
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setCoursier($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            if ($demande->getCoursier() === $this) {
                $demande->setCoursier(null);
            }
        }

        return $this;
    }
}
