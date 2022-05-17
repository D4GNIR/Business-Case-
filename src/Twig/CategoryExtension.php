<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    private CategoryRepository $categoryRepository;


    public function __construct(
        CategoryRepository $categoryRepository,

  
    ) {
        $this->categoryRepository = $categoryRepository;
  
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_one_categorie_by_label', [$this, 'getOneCategorieByLabel']),
        ];
    }

    public function getOneCategorieByLabel($id)
    {
        $categoryEntity = $this->categoryRepository->find($id);
        return $categoryEntity;
    }
}
