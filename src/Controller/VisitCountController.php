<?php

namespace App\Controller;

use App\Repository\VisitedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisitCountController extends AbstractController
{
    private VisitedRepository $visitedRepository;

    public function __construct(VisitedRepository $visitedRepository)
   {
       $this->visitedRepository = $visitedRepository;
   }
   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new \dateTime($minDateString);
       $maxDate = new \dateTime($maxDateString);

       $visitEntities = $this->visitedRepository->findVisitBetweenDates($minDate,$maxDate);
       dump($visitEntities);
       return $this->json(['data' => count($visitEntities)]);

   }

}
