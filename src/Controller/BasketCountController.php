<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketCountController extends AbstractController
{
    private CommandRepository $basketRepository;

    public function __construct(CommandRepository $basketRepository)
   {
       $this->basketRepository = $basketRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $basketEntities = $this->basketRepository->findBasketStatus100($minDate,$maxDate);
       return $this->json(count($basketEntities));

   }
}
