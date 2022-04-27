<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\VisitCountController;
use App\Repository\VisitedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitedRepository::class)]
#[ApiResource(
    collectionOperations: ['get',
    'get_total_visit_from_dates' =>[
    'method' => 'GET',
    'path' => '/visiteds/get_total_visit',
    'controller' =>VisitCountController::class
    ]],
    // Pour filtrer les api get et post
    itemOperations:[]
)]
class Visited
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $visitedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
