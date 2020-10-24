<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait CreationTypeTrait {

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('validation_groups', ['Default', 'creation']);
    }

}