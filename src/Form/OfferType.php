<?php

namespace App\Form;

use App\Entity\Offer;
use App\Form\DataTransformer\EuroTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => "Nom de l'offre",
        ])
        ->add('picture', TextType::class, [
            'label' => "Image",
        ])
        ->add('description', TextareaType::class, [
            'label' => "Description de l'offre",
        ])
        ->add('price', MoneyType::class, [
            'label' => "Prix de l'offre",
            'divisor' => 100
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
