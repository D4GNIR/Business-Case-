<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\AddCommandType;
use App\Repository\CommandRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommandController extends AbstractController
{
    private CommandRepository $commandRepository;
    private PaginatorInterface $paginator;
    private EntityManagerInterface $em;


    public function __construct(
        CommandRepository $commandRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $em

    ) {
        $this->commandRepository = $commandRepository;
        $this->paginator = $paginator;
        $this->em = $em;
     }

    #[Route('/admin/command_list', name: 'app_admin_command')]
    public function indexCommand(Request $request): Response
    {
        // $games = $this->gameRepository->findAll();
        $qb = $this->commandRepository->getQbAll();

        $pagination = $this->paginator->paginate(
            $qb, //La query
            $request->query->getInt('page',1), //Le numero de page de depart
            15 //Le nombre de résultat pas page
        );

        return $this->render('admin_command/index.html.twig', [
            // 'games' => $games,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/command/show/{id}', name: 'app_command_show')]
    public function showCommand(int $id): Response
    {
        $commandEntity = $this->commandRepository->findOneBy(['id'=>$id]);
 
        return $this->render('admin_command/show.html.twig', [
            'command' => $commandEntity
        ]);
    }

    #[Route('/admin/command/create', name: 'app_command_create')]
    public function addCommand(Request $request): Response
    {
        $commandEntity = new Command();
        $form = $this->createForm(AddCommandType::class, $commandEntity);
        $form->handleRequest($request);

        $commandEntity->setNumCommand(uniqid());
        $commandEntity->setCreatedAt(new DateTime('now'));

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($commandEntity);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_command');
        }

        return $this->render('admin_command/addCommand.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/command/edit/{id}', name: 'app_command_edit')]
    public function editGenre($id,Command $command, Request $request): Response
    {
        $command = $this->commandRepository->findOneBy(['id'=>$id]);

        $form = $this->createForm(AddCommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($command);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_command');
        }

        return $this->render('admin_command/addCommand.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/command/delete/{id}', name: 'app_command_delete')]
    public function deleteTopic($id,Request $request): Response
    {
        $commandEntity = $this->commandRepository->findOneBy(['id'=>$id]);

        $this->em->remove($commandEntity);
        $this->em->flush();

        return $this->redirectToRoute('app_admin_command');
    }
}
