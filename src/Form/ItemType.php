<?php

namespace App\Form;

use App\Entity\Items;
use App\Form\MediaType;
use App\Entity\Categories;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ItemType extends ApplicationType
{
    /** @var SluggerInterface */
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Menu", "Le nom du menu..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "La description du menu..."))
            ->add('price', IntegerType::class, $this->getConfiguration("Prix", "Le prix du menu..."))
            ->add('category', EntityType::class, $this->getConfiguration("Categorie", false, ['class' => Categories::class, 'choice_label' => 'title']))
            ->add("medias", CollectionType::class, [
                "entry_type" => MediaType::class,
                "entry_options" => [
                    "label" => false,
                ],
                "allow_add" => true,
                "allow_delete" => true,
                "by_reference" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
        ]);
    }
}