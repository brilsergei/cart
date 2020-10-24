<?php

namespace App\Validator;

use App\Entity\Cart;
use App\Entity\LineItem;
use App\Repository\LineItemRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NumberOfLineItemsPerCartValidator extends ConstraintValidator
{

    /**
     * @var \App\Repository\LineItemRepository
     */
    protected $lineItemRepository;

    public function __construct(LineItemRepository $lineItemRepository) {
        $this->lineItemRepository = $lineItemRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\NumberOfLineItemsPerCart */

        if (null === $value || '' === $value) {
            return;
        }

        if ($value instanceof LineItem && $value->getCart() instanceof Cart) {
            $lineItems = $value->getCart()->getLineItems();
            $count = \count($lineItems);
            foreach ($lineItems as $lineItem) {
                // Current line item is already in database and is included in $count.
                if ($lineItem === $value && $count > $constraint->max) {
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('{{ limit }}', (string) $constraint->max)
                        ->addViolation();
                }
            }

            // Current line item hasn't been yet saved and $count doesn't include it.
            if ($count >= $constraint->max) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ limit }}', (string) $constraint->max)
                    ->addViolation();
            }
        }
    }
}
