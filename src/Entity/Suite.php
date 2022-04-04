<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\SuiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiteRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['suite:read']],
    denormalizationContext: ['groups' => ['suite:write']],
    collectionOperations: [
        'get',
        "post" => ["security_post_denormalize" => "is_granted('SUITE_POST', object)"] ,
    ] ,
    itemOperations: [
        'put' => [
            "security" => "is_granted('SUITE_EDIT', object)"
        ],
        'delete' => [
            "security" => "is_granted('SUITE_DELETE', object)",
        ],
        'get' 
    ] 
)]

class Suite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["hotel:read","suite:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Groups(["hotel:read","user:read","suite:read",'suite:write'])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(["hotel:read","suite:read","suite:write"])]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["hotel:read","suite:read","suite:write"])]
    private $bookingLink;

    #[ORM\Column(type: 'integer')]
    #[Groups(["hotel:read","user:read","suite:read","suite:write"])]
    private $price;

    #[ORM\OneToMany(mappedBy: 'suite', targetEntity: Reservation::class, orphanRemoval: true)]
    #[Groups(["hotel:read"])]
    private $reservations;

    #[ORM\ManyToOne(targetEntity: Picture::class, inversedBy: 'suites')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["hotel:read","user:read","suite:read","suite:write"])]
    private $highlightPicture;

    #[ORM\ManyToMany(targetEntity: Picture::class, inversedBy: 'suites')]
    #[Groups(["hotel:read","suite:read"])]
    private $gallery;

    #[ORM\ManyToOne(targetEntity: Hotel::class, inversedBy: 'suites')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["user:read","suite:read","suite:write"])]
    private $hotel;

    #[ORM\OneToMany(mappedBy: 'suite', targetEntity: Comment::class, orphanRemoval: true)]
    #[Groups(["hotel:read"])]
    private $comments;


    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->gallery = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getBookingLink(): ?string
    {
        return $this->bookingLink;
    }

    public function setBookingLink(?string $bookingLink): self
    {
        $this->bookingLink = $bookingLink;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setSuite($this);
        }

        return $this;
    }

    public function removeReservation(reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSuite() === $this) {
                $reservation->setSuite(null);
            }
        }

        return $this;
    }

    public function getHighlightPicture(): ?Picture
    {
        return $this->highlightPicture;
    }

    public function setHighlightPicture(?Picture $highlightPicture): self
    {
        $this->highlightPicture = $highlightPicture;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(Picture $gallery): self
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery[] = $gallery;
        }

        return $this;
    }

    public function removeGallery(Picture $gallery): self
    {
        $this->gallery->removeElement($gallery);

        return $this;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setSuite($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getSuite() === $this) {
                $comment->setSuite(null);
            }
        }

        return $this;
    }

}
