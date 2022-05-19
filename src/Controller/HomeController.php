<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private BasketService $basketService;


    public function __construct(
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        BasketService $basketService
  
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->basketService = $basketService;
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

    #[Route('/panier', name: 'app_basket')]
    public function basket(): Response
    {
        
        $basketEntity = $this->basketService->getBasket($this->getUser());

        return $this->render('home/basket.html.twig', [
            'basket' => $basketEntity,
        ]);
    }

    #[Route('/buy/{id}', name: 'app_buy')]
    public function buy($id,Request $request)
    {
        $productEntity = $this->productRepository->find($id);
        $basketEntity = $this->basketService->addProductToBasket($this->getUser(),$productEntity);

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/delete/{id}', name: 'app_del')]
    public function delete($id,Request $request)
    {
        $productEntity = $this->productRepository->find($id);
        $basketEntity = $this->basketService->removeProductFromBasket($this->getUser(),$productEntity);

        return $this->redirectToRoute('app_basket');
    }

    #[Route('/erreur', name: 'app_error')]
    public function error(): Response
    {

        
        return $this->render('error/error404.html.twig', [

        ]);
    }
}
