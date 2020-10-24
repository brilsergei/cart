<?php

namespace App\Controller;

use App\Entity\Cart;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 *
 * @package App\Controller
 *
 * @Route("/api/v1")
 */
class CartController extends ApiController {

    /**
     * @Route("/carts", name="create_cart", methods={"POST"})
     */
    public function create(Request $request)
    {
        $cart = new Cart();
        // TODO Allow to add line items on cart creation.
        $entityManager = $this->getDoctrine()
            ->getManager();
        $entityManager->persist($cart);
        try
        {
            $entityManager->flush();
        } catch (ORMException $exception)
        {
            $result = ['errors' => ['Unable to store new cart.']];
            return $this->json($result, Response::HTTP_INSUFFICIENT_STORAGE);
        }

        return $this->json($cart, Response::HTTP_CREATED);
    }

}