<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductType;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @package App\Controller
 *
 * @Route("/api/v1")
 */
class ProductController extends ApiController
{
    /**
     * @Route("/product", name="create_product", methods={"POST"})
     */
    public function create(Request $request)
    {
        try {
            $data = $this->getJson($request);
        }
        catch (HttpException $exception) {
            $result = ['errors' => [$exception->getMessage()]];
            return $this->json($result, $exception->getStatusCode());
        }
        $product = new Product();
        $form = $this->createForm(CreateProductType::class, $product);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()
                ->getManager();
            $entityManager->persist($product);
            try
            {
                $entityManager->flush();
            } catch (ORMException $exception)
            {
                $result = ['errors' => ['Unable to store new product.']];
                return $this->json($result, Response::HTTP_INSUFFICIENT_STORAGE);
            }

            return $this->json($product, Response::HTTP_CREATED);
        }

        return $this->handleFormErrors($form);
    }
}
