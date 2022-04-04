<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        "post" => ["security" => "is_granted('ROLE_MANAGER')"] ,
    ] ,
    itemOperations: [
        'delete' => [
            "security" => "is_granted('PICT_DELETE', object)",
        ],
        'get' 
    ] 
)]

class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(["hotel:read","user:read"])]
    private $shortDescription;

    #[ORM\OneToMany(mappedBy: 'highlightPicture', targetEntity: Suite::class)]
    private $suites;

    #[ORM\ManyToMany(mappedBy: 'gallery', targetEntity: Suite::class)]
    private $galleries;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["hotel:read","user:read"])]
    private $public_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["hotel:read","user:read"])]
    private $secure_url;

    public function __construct()
    {
        $this->suites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

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
            $suite->setHighlightPicture($this);
        }

        return $this;
    }

    public function removeSuite(Suite $suite): self
    {
        if ($this->suites->removeElement($suite)) {
            // set the owning side to null (unless already changed)
            if ($suite->getHighlightPicture() === $this) {
                $suite->setHighlightPicture(null);
            }
        }

        return $this;
    }

        /**
     * @return Collection<int, Suite>
     */
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function getPublicId(): ?string
    {
        return $this->public_id;
    }

    public function setPublicId(?string $public_id): self
    {
        $this->public_id = $public_id;

        return $this;
    }

    public function getSecureUrl(): ?string
    {
        return $this->secure_url;
    }

    public function setSecureUrl(?string $secure_url): self
    {
        $this->secure_url = $secure_url;

        return $this;
    }



}
