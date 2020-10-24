<?php

namespace App\Controller;

use App\Entity\LineItem;
use App\Form\CreateLineItemType;
use App\Repository\CartRepository;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LineItemController
 *
 * @package App\Controller
 *
 * @Route("/api/v1/cart/{cart_id}")
 */
class LineItemController extends ApiController {

    /**
     * @Route("/line-items", name="add_to_cart", methods={"POST"})
     */
    public function create($cart_id, Request $request, CartRepository $cartRepository)
    {
        // TODO Better throw exceptions in order to avoid code duplication (see method update)
        try {
            $data = $this->getJson($request);
        }
        catch (HttpException $exception) {
            $result = ['errors' => [$exception->getMessage()]];
            return $this->json($result, $exception->getStatusCode());
        }
        $lineItem = new LineItem();
        $form = $this->createForm(CreateLineItemType::class, $lineItem);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $cartRepository->find($cart_id);
            $lineItem->setCart($cart);
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($lineItem);
            try
            {
                $entityManager->flush();
            } catch (ORMException $exception)
            {
                $result = ['errors' => ['Unable to add the product to the cart.']];
                return $this->json($result, Response::HTTP_INSUFFICIENT_STORAGE);
            }

            return $this->json($lineItem, Response::HTTP_CREATED);
        }

        return $this->handleFormErrors($form);
    }

}