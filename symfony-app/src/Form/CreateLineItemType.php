<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;

class CreateLineItemType extends LineItemType {

    use CreationTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder
            ->add('product')
        ;
    }

}