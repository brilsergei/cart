<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
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
    private const MAX_PRODUCTS_PER_PAGE = 3;

    /**
     * @Route("/products", name="create_product", methods={"POST"})
     */
    public function create(Request $request)
    {
        // TODO Better throw exceptions in order to avoid code duplication (see method update)
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

    /**
     * @Route("/products/{id}", name="show_product", methods={"GET"})
     */
    public function show($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if ($product instanceof Product) {
            return $this->json($product);
        }

        return new Response('', Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/products/{id}", name="delete_product", methods={"DELETE"})
     */
    public function delete($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if (!$product instanceof Product) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->getDoctrine()
            ->getManager();
        $entityManager->remove($product);

        try
        {
            $entityManager->flush();
        } catch (ORMException $exception)
        {
            $result = ['errors' => ['Unable to delete the product.']];
            return $this->json($result, Response::HTTP_INSUFFICIENT_STORAGE);
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/products/{id}", name="update_product", methods={"PATCH"})
     */
    public function update($id, ProductRepository $productRepository, Request $request)
    {
        // TODO Better throw exceptions in order to avoid code duplication (see method create)
        try {
            $data = $this->getJson($request);
        }
        catch (HttpException $exception) {
            $result = ['errors' => [$exception->getMessage()]];
            return $this->json($result, $exception->getStatusCode());
        }

        $product = $productRepository->find($id);
        if (!$product instanceof Product) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ProductType::class, $product);
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
                $result = ['errors' => ['Unable to update the product.']];
                return $this->json($result, Response::HTTP_INSUFFICIENT_STORAGE);
            }

            return $this->json($product, Response::HTTP_OK);
        }

        return $this->handleFormErrors($form);
    }

    /**
     * @Route("/products", name="list_products", methods={"GET"})
     */
    public function list(Request $request, ProductRepository $productRepository)
    {
        $page = $request->get('page') ?? 0;
        $products = $productRepository->findBy(
            [],
            ['id' => 'asc'],
            static::MAX_PRODUCTS_PER_PAGE,
            static::MAX_PRODUCTS_PER_PAGE * $page
        );

        return $this->json($products, Response::HTTP_OK);
    }
}
