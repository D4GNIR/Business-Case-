<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionCommandsBasketsController extends AbstractController
{
    private CommandRepository $conversionCommandsBasketsRepository;

    public function __construct(CommandRepository $conversionCommandsBasketsRepository)
   {
       $this->conversionCommandsBasketsRepository = $conversionCommandsBasketsRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $basketEntities = $this->conversionCommandsBasketsRepository->findBasketStatus100($minDate,$maxDate);
       $CommandsEntities = $this->conversionCommandsBasketsRepository->findCommandsBetweenDates($minDate,$maxDate);
       $result = (count($basketEntities)/count($CommandsEntities))*100;
       return $this->json(['data' => (float)number_format($result,2)]);

   }
}
