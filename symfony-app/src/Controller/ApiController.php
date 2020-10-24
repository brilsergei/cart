<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ApiController extends AbstractController
{

    protected function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid JSON.');
        }

        return $data;
    }

    protected function handleFormErrors(FormInterface $form)
    {
        $errors = $form->getErrors(true);
        $result = ['errors' => []];
        foreach ($errors as $error) {
            $result['errors'][] = $error->getMessage();
        }

        return $this->json($result, Response::HTTP_BAD_REQUEST);
    }

}