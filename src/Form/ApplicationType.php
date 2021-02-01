<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Class ApplicationType.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ApplicationType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un formulaire.
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    public function getConfiguration(string $label, string $placeholder, array $options = []): array
    {
        return array_merge_recursive([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}