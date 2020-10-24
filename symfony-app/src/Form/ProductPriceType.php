<?php

namespace App\Form;

use App\Entity\ProductPrice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', null, ['empty_data' => function (FormInterface $form) {
                    return (string) $form->getParent()
                        ->getData()
                        ->getAmount();
                }]
            )
            ->add('currency', null, ['empty_data' => function (FormInterface $form) {
                    return $form->getParent()
                        ->getData()
                        ->getCurrency();
                }]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductPrice::class,
        ]);
    }
}
