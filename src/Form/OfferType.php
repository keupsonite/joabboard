<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Offer;
use App\Entity\OfferCategory;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', TextType::class)
            ->add('description', TextType::class)
            ->add('status', CheckboxType::class, [
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $repository) {
                    return $repository
                        ->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC')
                    ;
                },
                'choice_label' => 'email',
            ])
            ->add('type', EntityType::class, [
                'class' => \App\Entity\OfferType::class,
                'choice_label' => function (\App\Entity\OfferType $offerType) {
                    return $offerType->getName();
                },
            ])
            ->add('category', EntityType::class, [
                'class' => OfferCategory::class,
                'choice_label' => function (OfferCategory $offerCategory) {
                    return $offerCategory->getName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
