<?php

namespace App\Form;

use App\Entity\Command;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',TextType::class, [
                'label' =>'Statut commande',
                'attr' => [
                    'placeholder' => '100 = Panier, 200 = Payée, 300 = Expediée, 400 = Remboursée, 500 = Echouée',
                ]])
            ->add('user',EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'attr' => [
                    'class' => 'col-3'
                ]                  
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn btn-warning'
            ]
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
