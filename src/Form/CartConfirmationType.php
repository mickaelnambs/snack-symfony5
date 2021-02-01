<?php

namespace App\Form;

use App\Entity\Purchases;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CartConfirmationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, $this->getConfiguration("Nom complet", "Nom complet pour la livraison..."))
            ->add('address', TextareaType::class, $this->getConfiguration("Adresse complète", "Adresse complète pour la livraison..."))
            ->add('postalCode', TextType::class, $this->getConfiguration("Code Postal", "Code postal pour la livraison..."))
            ->add('city', TextType::class, $this->getConfiguration("Ville", "Ville pour la livraison..."));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchases::class,
        ]);
    }
}