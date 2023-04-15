<?php

namespace App\Controller;

use App\Entity\Asociados;
use App\Entity\Imagenes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {



        $entityManager = $doctrine->getManager();


        if (isset($_GET['category'])){

          $imagenes = $entityManager->getRepository(Imagenes::class)->findImageCategory((int)$_GET['category']);

        }else{

           $imagenes = $entityManager->getRepository(Imagenes::class)->findImageCategory(1);
        }



        $entityManager = $doctrine->getManager();
        $asociados = $entityManager->getRepository(Asociados::class)->generar3Asociados($entityManager);




        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'imagenes'=>$imagenes,
            'asociados'=>$asociados
        ]);
    }


}
