<?php

namespace App\Entity;

use App\Controller\TotalProductSoldController;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    collectionOperations: ['get','post',
    'get_total_product' =>[
         'method' => 'GET',
         'path' => 'products/get_total_product',
         'controller' => TotalProductSoldController::class
    ]],
    // Pour filtrer les api get et post
    itemOperations:[],
    normalizationContext:['groups' => ['Product_Category']]

)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre nom de produit ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre nom de produit ne peux pas etre vide.'
    ),Assert\Length(        
        min: 2,
        max: 43,
        minMessage: 'Votre nom de produit doit au moins faire {{ limit }} caractères de long.',
        maxMessage: 'Votre nom de produit doit faire moins de {{ limit }} caractères de long.',)
    ,Assert\Type(
        type: 'string',
        message: 'Votre nom de produit n\'est pas valide.',
    )
    ]
    #[Groups(['Product_Category','Command_Product'])]
    private $label;

    #[ORM\Column(type: 'text')]
    #[
        Assert\NotNull(
        message: 'Votre description ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre description ne peux pas etre vide.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre description n\'est pas valide.',
    )
    ]
    #[Groups(['Product_Category'])]
    private $decription;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\NotNull(
        message: 'Votre prix ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre prix  ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre prix  ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'integer',
        message: 'Votre prix  n\'est pas valide.',
    )
    ]
    #[Groups(['Product_Category','Command_Product'])]
    private $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[
    Assert\NotBlank(
        message: 'Votre stock  ne peux pas etre vide.'
    ),Assert\Type(
        type: 'integer',
        message: 'Votre stock  n\'est pas valide.',
    )
    ]
    #[Groups(['Product_Category'])]
    private $stock;

    #[ORM\Column(type: 'boolean')]
    #[
        Assert\NotNull(
            message: 'Votre champ actif ne peux pas etre vide.'
        ),
        Assert\Type(
            type: 'bool',
            message: 'Votre champ actif n\'est pas valide.',
        )
        ]
        #[Groups(['Product_Category'])]
    private $isActive;

    #[ORM\ManyToMany(targetEntity: Command::class, inversedBy: 'products')]
    #[Groups(['Product_Category'])]
    private $commands;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[Groups(['Product_Category','Command_Product'])]
    private $brand;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    #[Groups(['Product_Category'])]
    private $reviews;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups(['Product_Category'])]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, cascade:['persist','remove'])]
    #[Groups(['Product_Category'])]
    private $productPictures;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): self
    {
        $this->decription = $decription;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        $this->commands->removeElement($command);

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }
}
