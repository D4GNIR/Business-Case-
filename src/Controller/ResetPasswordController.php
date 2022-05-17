<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class ResetPasswordController extends AbstractController
{
    
    private UserRepository $userRepository;
    private ResetPasswordRepository $resetPasswordRepository;


    public function __construct(
        userRepository $userRepository,
        ResetPasswordRepository $resetPasswordRepository,

  
    ) {
        $this->userRepository = $userRepository;
        $this->resetPasswordRepository = $resetPasswordRepository;

    }

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request,EntityManagerInterface $em,MailerInterface $mailerInterface): Response
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailEntry = $form->getData();
            $emailEntry = $emailEntry['email'];
            $userEntity = $this->userRepository->findOneBy(['email' => $emailEntry]);
            if ($userEntity !== null) {
               $resetPasswordEntity = new ResetPassword();
               $resetPasswordEntity->setIsReset(false);
               $resetPasswordEntity->setToken(uniqid());
               $resetPasswordEntity->setCreatedAt( new DateTime());
               $resetPasswordEntity->setUser($userEntity);
               $em->persist($resetPasswordEntity);
               $em->flush();

               $email = new Email();
               $email
                   ->from('animalri167@gmail.com')
                //    ->to($emailEntry)
                   ->to('tanker4342@yahoo.fr')
                   ->subject('Modifier votre mot de passe')
                   ->html('
                   <h2>Réinitialisation du mot de passe</h2>
                   <p>Vous recevez ce message parce qu\'une réinitialisation du mot de passe de votre compte utilisateur a été demandée.

                   Si vous n\'avez pas demandé une réinitialisation du mot de passe, vous pouvez IGNORER et EFFACER cet email. Continuez uniquement si vous souhaitez que votre mot de passe soit réinitialisé.</p>
                   <a href="http://127.0.0.1:8000/resetPassword/'. $resetPasswordEntity->getToken().'">Changer votre mot de passe</a>
                   ');
       
                   $mailerInterface->send($email);
                   return $this->redirectToRoute('app_redirect_password');
            }
        }


        return $this->render('reset_password/index.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
    #[Route('/resetPassword/{token}', name: 'app_reset_password_confirm')]
    public function resetPassword(Request $request,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em,$token): Response
    {
        $resetPasswordEntity = $this->resetPasswordRepository->findOneBy(['token' => $token]);
        $user = $resetPasswordEntity->getUser();

        if ($resetPasswordEntity->getIsReset() === false) {
            $form = $this->createForm(NewPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            
                $passwordEntry = $form->getData();
                $passwordEntity = $passwordEntry['password'];
    
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $passwordEntity
                );
    
                $user->setPassword($hashedPassword);
                $resetPasswordEntity->setIsReset(true);
                $em->persist($resetPasswordEntity,$user);
                $em->flush();
                return $this->redirectToRoute('app_redirect_password_ok');
            }
            return $this->render('reset_password/modify.html.twig', [
                'form' => $form->createView(),
                
            ]);
        }else{
            return $this->redirectToRoute('app_redirect_password_nok');
        }
        

    }

    #[Route('/reset/password/redirect', name: 'app_redirect_password')]
    public function redirected(): Response
    {

        return $this->render('reset_password/redirect.html.twig');
    }

    #[Route('/reset/password/redirectOk', name: 'app_redirect_password_ok')]
    public function redirectedOk(): Response
    {

        return $this->render('reset_password/redirectOK.html.twig');
    }

    #[Route('/reset/password/redirectNok', name: 'app_redirect_password_nok')]
    public function redirectedNok(): Response
    {

        return $this->render('reset_password/redirectNoK.html.twig');
    }
}
