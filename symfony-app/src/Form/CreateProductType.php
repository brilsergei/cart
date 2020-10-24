<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;

class CreateProductType extends ProductType
{
    use CreationTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('price', CreateProductPriceType::class)
        ;
    }
}
