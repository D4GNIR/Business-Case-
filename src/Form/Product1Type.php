<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Product1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label',TextType::class, [
                'label' =>'Label',
                'attr' => [
                    'class' => 'mt-3 mb-3 form-control',
                ]])
            ->add('decription',TextareaType::class, [
                'label' =>'Decription',
                'attr' => [
                    'class' => 'mt-3 mb-3 form-control',
                ]])
            ->add('price',IntegerType::class, [
                'label' =>'Prix',
                'attr' => [
                    'class' => 'mt-3 mb-3 form-control',
                ]])
            ->add('stock',IntegerType::class, [
                'label' =>'stock',
                'attr' => [
                    'class' => 'mt-3 mb-3 form-control',
                ]])
            ->add('isActive',CheckboxType::class, array(
                'required' => false,
                'label' =>'produit actif',
                'value' => true,
                'attr' => [
                    'class' => 'm-3',
                ]))
            
            ->add('brand',EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'label',
                'mapped' => false,
                'multiple' => false,
                'expanded' => true,
                'attr'=>[
                    'class' => 'checkbox_content form-check'
                ]
            ])
            ->add('categories',EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('C')
                        ->where('C.categoryParent IS NOT NULL');
                },
                'mapped' => false,
                'multiple' => true,
                'expanded' => true,
                'attr'=>[
                    'class' => 'checkbox_content form-check'
                ]
            ])
            ->add('image', FileType::class,[
                'mapped' => false,
                
                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
