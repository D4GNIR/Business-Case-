<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AbandonnedBasketController;
use App\Controller\BasketCountController;
use App\Controller\CommandRecurrenceController;
use App\Controller\CommandsAverageController;
use App\Controller\CommandsCountController;
use App\Controller\CommandsSalesController;
use App\Controller\ConversionBasketsVisitController;
use App\Controller\ConversionCommandsBasketsController;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
        'get_basket_count' =>[
             'method' => 'GET',
             'path' => '/commands/get_basket_count',
             'controller' => BasketCountController::class
        ],
        'get_commands_count' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_count',
            'controller' => CommandsCountController::class
            ],
        'get_commands_sales' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_sales',
            'controller' => CommandsSalesController::class
                ],
        'get_commands_average' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_average',
            'controller' => CommandsAverageController::class
                ],
        'get_commands_reccurence' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_reccurence',
            'controller' => CommandRecurrenceController::class
                ],
        'get_commands_conversion' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_conversion',
            'controller' => ConversionCommandsBasketsController::class
                ],
        'get_commands_abandonned' =>[
            'method' => 'GET',
            'path' => '/commands/get_commands_abandonned',
            'controller' => AbandonnedBasketController::class
                ],
        'get_converted_basket' =>[
            'method' => 'GET',
            'path' => '/commands/get_converted_basket',
            'controller' => ConversionBasketsVisitController::class
                ]
        ],
    // Pour filtrer les api get et post
    itemOperations:['get'],
    normalizationContext:['groups' => ['Command_Product']]
)]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'user.email' => 'exact'])]
// http://127.0.0.1:8000/api/commands?email=carson52@hotmail.com
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\NotNull(
        message: 'Votre numéro de commande ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre numéro de commande ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre numéro de commande ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre numéro de commande n\'est pas valide.',
    )
    ]
    #[Groups(['Command_Product'])]
    private $numCommand;

    #[ORM\Column(type: 'datetime')]
    #[Assert\EqualTo('today',
    message: 'Votre date n\'est pas conforme.'
    ),Assert\Type(
    type: 'datetime',
    message: 'Votre date n\'est pas conforme.'
    )]
    #[Groups(['Command_Product'])]
    private $createdAt;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\NotNull(
        message: 'Votre status ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre status ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre status ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre status n\'est pas valide.',
    )
    ]
    private $status;

    #[ORM\Column(type: 'integer')]
    #[
        Assert\NotNull(
        message: 'Votre prix total ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre prix total  ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre prix total  ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre prix total  n\'est pas valide.',
    )
    ]
    #[Groups(['Command_Product'])]
    private $totalPrice;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private $address;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'commands')]
    #[Groups(['Command_Product'])]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCommand(): ?int
    {
        return $this->numCommand;
    }

    public function setNumCommand(int $numCommand): self
    {
        $this->numCommand = $numCommand;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCommand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCommand($this);
        }

        return $this;
    }
}
