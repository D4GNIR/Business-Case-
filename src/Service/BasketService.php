<?php

namespace App\Service;

use App\Entity\Command;
use App\Enum\EnumCommand;
use App\Repository\CommandRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class BasketService {

    private CommandRepository $commandRepository;
    private EntityManagerInterface $em;



    public function __construct(
        CommandRepository $commandRepository,
        EntityManagerInterface $em
  
    ) {
        $this->commandRepository = $commandRepository;
        $this->em = $em;
    }

    

    public function getBasket($user){

        $basketEntity = $this->commandRepository->getBasketByUser($user);

        if($basketEntity === null){
            $basketEntity = new Command();
            $basketEntity->setCreatedAt(new DateTime('now'));
            $basketEntity->setNumCommand(uniqid());
            $basketEntity->setStatus(EnumCommand::BASKET_BASE);
            $basketEntity->setUser($user);
            $this->em->persist($basketEntity);
            $this->em->flush();
            
        } 
        return $basketEntity;
    }

    public function addProductToBasket($user,$productEntity) : void{
        
        $basketEntity = $this->getBasket($user);
        $basketEntity->addProduct($productEntity);
        
        $this->em->persist($basketEntity);
        $this->em->flush();
    }

    public function removeProductFromBasket($user,$productEntity) : void{
        
        $basketEntity = $this->getBasket($user);
        $basketEntity->removeProduct($productEntity);
        $this->em->remove($basketEntity);
        $this->em->flush();
    }
    
}