<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;


    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository
  
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $categoriesEntity = $this->categoryRepository->findAll();
        $topThree = $this->productRepository->getThreeMostSellProduct();
        return $this->render('home/index.html.twig', [
            'categoriesEntity' => $categoriesEntity,
            'items' => $topThree,
        ]);
    }

    // Récupérer une catégorie avec son label
    #[Route('/categorie/{id}', name: 'categories')]
    public function getOneCategorieByLabel(string $id): Response
    {
        $categoryEntity = $this->categoryRepository->find($id);

        return $this->render('animal_page/index.html.twig', [
            'category' => $categoryEntity,
        ]);
    }
}
