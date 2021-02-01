<?php

namespace App\Form;

use App\Entity\Medias;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MediaType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, $this->getConfiguration("Image", false, [
                "required" => false,
                "constraints" => [new Image()]
            ]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Medias::class,
        ]);
    }
}