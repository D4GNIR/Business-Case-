<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandRecurrenceController extends AbstractController
{
    private CommandRepository $commandReccurrenceRepository;

    public function __construct(CommandRepository $commandReccurrenceRepository)
   {
       $this->commandReccurrenceRepository = $commandReccurrenceRepository;
   }
    public function __invoke(Request $request)
    {
 
        $minDateString = $request->query->get('min_date');
        $maxDateString = $request->query->get('max_date');
 
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);
 
     
        $commandRecurrenceInDates = $this->commandReccurrenceRepository->findCommandsByUserCreatedInDates($minDate,$maxDate);
        $commandRecurrenceOutDates = $this->commandReccurrenceRepository->findCommandsByUserCreatedOutDate($minDate);
        $result = (count($commandRecurrenceInDates)/count($commandRecurrenceOutDates))*100;
        return $this->json((float)number_format($result,2));
 
    }
}
