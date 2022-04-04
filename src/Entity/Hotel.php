<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ApiResource( 
    normalizationContext: ['groups' => ['hotel:read']],
    denormalizationContext: ['groups' => ['hotel:write']],
    collectionOperations: [
        'get' => [ "security" => "is_granted('PUBLIC_ACCESS')" ] ,
        'post' => [ "security" => "is_granted('ROLE_ADMIN')" ] ,
    ] ,
    itemOperations: [
        'put' =>[ "security" => "is_granted('ROLE_ADMIN')" ] ,
        'delete' => [
            "security" => "is_granted('ROLE_ADMIN')",
            "security_message" => "Only admins can delete users.",
        ],
        'get' => ['groups' => ['hotel:read']]
    ] 

    
)]
class Hotel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["hotel:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["hotel:read","user:read","hotel:write"])]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["hotel:read","user:read","hotel:write"])]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["hotel:read","hotel:write"])]
    private $address;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'hotels')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["hotel:read","hotel:write"])]
    private $manager;

    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Suite::class, orphanRemoval: true)]
    #[Groups(["hotel:read"])]
    private $suites;

    #[ORM\Column(type: 'text')]
    #[Groups(["hotel:read","hotel:write"])]
    private $description;

    public function __construct()
    {
        $this->suites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, Suite>
     */
    public function getSuites(): Collection
    {
        return $this->suites;
    }

    public function addSuite(Suite $suite): self
    {
        if (!$this->suites->contains($suite)) {
            $this->suites[] = $suite;
            $suite->setHotel($this);
        }

        return $this;
    }

    public function removeSuite(Suite $suite): self
    {
        if ($this->suites->removeElement($suite)) {
            // set the owning side to null (unless already changed)
            if ($suite->getHotel() === $this) {
                $suite->setHotel(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
