<?php

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewUserController extends AbstractController
{
    private UserRepository $newUserRepository;

    public function __construct(UserRepository $newUserRepository)
    {
       $this->newUserRepository = $newUserRepository;
    }

    public function __invoke(Request $request)
    {
 
        $minDateString = $request->query->get('min_date');
        $maxDateString = $request->query->get('max_date');
 
        $minDate = new DateTime($minDateString);
        $maxDate = new DateTime($maxDateString);
 
      
        $newUsersEntities = $this->newUserRepository->findNewUsers($minDate,$maxDate);
        
        return $this->json(['data' => count($newUsersEntities)]);
 
    }
}
