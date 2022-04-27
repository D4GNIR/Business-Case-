<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use App\Repository\VisitedRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversionBasketsVisitController extends AbstractController
{
    private CommandRepository $conversionBasketsRepository;
    private VisitedRepository $conversionVisitRepository;

    public function __construct(CommandRepository $conversionBasketsRepository,VisitedRepository $conversionVisitRepository)
   {
       $this->conversionBasketsRepository = $conversionBasketsRepository;
       $this->conversionVisitRepository = $conversionVisitRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $visitEntities = $this->conversionVisitRepository->findVisitBetweenDates($minDate,$maxDate);
       $basketEntities = $this->conversionBasketsRepository->findBasketStatus100($minDate,$maxDate);
       $result = (count($visitEntities)/count($basketEntities))*100;
       return $this->json((float)number_format($result,2));

   }
}
