<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    collectionOperations: ['get','post'],
    // Pour filtrer les api get et post
    itemOperations:['get']
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(
        message: 'Votre note ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre note ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre prix total ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'integer',
        message: 'Votre prix total  n\'est pas valide.',
    ),Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Votre note doit etre comprises entre {{ min }} et {{ max }}',
    )
    ]
    private $note;

    #[ORM\Column(type: 'text', nullable: true)]
    #[
        Assert\NotNull(
        message: 'Votre contenu ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre contenu ne peux pas etre vide.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre contenu n\'est pas valide.',
    )
    ]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Assert\EqualTo('today',
    message: 'Votre date n\'est pas conforme.'
    ),Assert\Type(
    type: 'datetime',
    message: 'Votre date n\'est pas conforme.'
    )]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'reviews')]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
