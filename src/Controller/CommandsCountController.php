<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandsCountController extends AbstractController
{
    private CommandRepository $commandCountRepository;

    public function __construct(CommandRepository $commandCountRepository)
   {
       $this->commandCountRepository = $commandCountRepository;
   }

   public function __invoke(Request $request)
   {

       $minDateString = $request->query->get('min_date');
       $maxDateString = $request->query->get('max_date');

       $minDate = new DateTime($minDateString);
       $maxDate = new DateTime($maxDateString);

       $commandCountEntities = $this->commandCountRepository->findCommandsBetweenDates($minDate,$maxDate);
       return $this->json(count($commandCountEntities));

   }
}
