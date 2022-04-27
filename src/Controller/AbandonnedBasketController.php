<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbandonnedBasketController extends AbstractController
{
    private CommandRepository $abandonnedBasketsRepository;

    public function __construct(CommandRepository $abandonnedBasketsRepository)
   {
       $this->abandonnedBasketsRepository = $abandonnedBasketsRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $basketEntities = $this->abandonnedBasketsRepository->findBasketStatus100($minDate,$maxDate);
       $CommandsEntities = $this->abandonnedBasketsRepository->findCommandsBetweenDates($minDate,$maxDate);
       $result = 100-((count($basketEntities)/count($CommandsEntities))*100);
       return $this->json((float)number_format($result,2));

   }
}

