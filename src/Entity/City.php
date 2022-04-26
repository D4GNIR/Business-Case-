<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(
    collectionOperations: ['get','post'],
    // Pour filtrer les api get et post
    itemOperations:['get'])
]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial'])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre nom de ville ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre nom de ville ne peux pas etre vide.'
    ),Assert\Length(        
        min: 2,
        max: 43,
        minMessage: 'Votre nom de ville doit au moins faire {{ limit }} caractères de long.',
        maxMessage: 'Votre nom de ville doit faire moins de {{ limit }} caractères de long.',)
        ]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre code postal ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre code postal ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre code postal ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre code postal n\'est pas valide.',
    )
    ]
    private $postalCode;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Address::class)]
    private $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setCity($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getCity() === $this) {
                $address->setCity(null);
            }
        }

        return $this;
    }
}
