<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandsSalesController extends AbstractController
{
    private CommandRepository $commandSalesRepository;

    public function __construct(CommandRepository $commandSalesRepository)
   {
       $this->commandSalesRepository = $commandSalesRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $total = 0;

       $commandCountEntities = $this->commandSalesRepository->commandsSalesAmount($minDate,$maxDate);
       foreach ($commandCountEntities as $commandEntity) {
           $total += $commandEntity->getTotalPrice();
       }
       return $this->json(['data' => $total]);

   }
}
