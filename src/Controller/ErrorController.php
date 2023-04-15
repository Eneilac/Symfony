<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/error", name="app_error")
 */
class ErrorController extends AbstractController
{
    public function show(): Response
    {
        return $this->render('error/error.html.twig', [
            'controller_name' => 'ErrorController'
        ]);
    }
}