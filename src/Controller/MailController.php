<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function index(MailerInterface $mailerInterface): Response
    {

        $email = new Email();
        $email
            ->from('animalri167@gmail.com')
            ->to('tanker4342@yahoo.fr')
            ->subject('Wesh')
            ->html('<p style="color : red"> pppppppppppppaaze </p>');

            $mailerInterface->send($email);

        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }
}
