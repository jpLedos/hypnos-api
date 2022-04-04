<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['comment:read']] ,
    denormalizationContext: ['groups' => ['comment:write']] ,
    itemOperations: [
        'put' => [
            "security" => "is_granted('SUITE_RIGHT', object)"
        ],
        'delete' => [
            "security" => "is_granted('SUITE_RIGHT', object)",
        ],
        'get' 
    ]
)]

class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["hotel:read"])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(["hotel:read", "comment:write"])]
    private $comment;

    #[ORM\Column(type: 'integer')]
    #[Groups(["hotel:read", "comment:write"])]
    private $note;

    #[ORM\ManyToOne(targetEntity: Suite::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["comment:write"])]
    private $suite;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["hotel:read"])]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getSuite(): ?Suite
    {
        return $this->suite;
    }

    public function setSuite(?Suite $suite): self
    {
        $this->suite = $suite;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
