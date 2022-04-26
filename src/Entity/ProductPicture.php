<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    // Pour filtrer les api get et post
    itemOperations:['get']
)]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre nom d\'image ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre nom d\'image ne peux pas etre vide.'
    ),Assert\Length(        
        min: 2,
        max: 43,
        minMessage: 'Votre nom d\'image doit au moins faire {{ limit }} caractères de long.',
        maxMessage: 'Votre nom d\'image doit faire moins de {{ limit }} caractères de long.',)
    ,Assert\Type(
        type: 'string',
        message: 'Votre nom d\'image n\'est pas valide.',
    )
    ]
    private $libele;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre chemin d\'image ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre chemin d\'image ne peux pas etre vide.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre chemin d\'image n\'est pas valide.',
    )
    ]
    private $path;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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
