<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandsAverageController extends AbstractController
{
    private CommandRepository $commandAverageRepository;

    public function __construct(CommandRepository $commandAverageRepository)
   {
       $this->commandAverageRepository = $commandAverageRepository;
   }
    public function __invoke(Request $request)
    {
 
        $minDateString = $request->query->get('min_date');
        $maxDateString = $request->query->get('max_date');
 
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);
 
        $total = 0;
        $i = 0;
        $commandCountEntities = $this->commandAverageRepository->commandsSalesAverage($minDate,$maxDate);
        foreach ($commandCountEntities as $commandEntity) {
            $total += $commandEntity->getTotalPrice();
            $i++;
        }
        return $this->json(['data' => ($total/$i)]);
 
    }
}
