<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    collectionOperations: ['get','post'],
    // Pour filtrer les api get et post
    itemOperations:['get']
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre numéro de rue ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre numéro de rue ne peux pas etre vide.'
    ),
    Assert\Positive(
        message: 'Votre numéro de rue ne peux pas etre négatif.'
    ),Assert\Type(
        type: 'string',
        message: 'Votre numéro de rue n\'est pas valide.',
    )
    ]
    #[Groups(['User_Adress'])]
    private $streetNumber;

    #[ORM\Column(type: 'string', length: 255)]
    #[
        Assert\NotNull(
        message: 'Votre numéro de rue ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre numéro de rue ne peux pas etre vide.'
    ),Assert\Length(        
        min: 2,
        max: 43,
        minMessage: 'Votre nom de rue doit au moins faire {{ limit }} caractères de long.',
        maxMessage: 'Votre nom de rue doit faire moins de {{ limit }} caractères de long.',)
        ]
        #[Groups(['User_Adress'])]
    private $streetName;
    
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'adresses')]
    private $users;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Assert\NotNull(
        message: 'Votre numéro de rue ne peux pas etre nul.'
    ),Assert\NotBlank(
        message: 'Votre numéro de rue ne peux pas etre vide.'
    )
    ]
    private $city;

    #[ORM\OneToMany(mappedBy: 'address', targetEntity: Command::class)]
    private $commands;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAdress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeAdress($this);
        }

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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
            $command->setAddress($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAddress() === $this) {
                $command->setAddress(null);
            }
        }

        return $this;
    }
}
