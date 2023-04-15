<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Entity\Imagenes;
use App\Form\ImagenGaleriaType;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImagenGaleriaController extends AbstractController
{
    /**
     * @Route("/imagen_galeria", name="imagen_galeria")
     */
    public function index(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, LoggerInterface $logger): Response
    {
        $imagen = new Imagenes();
        $form = $this->createForm(ImagenGaleriaType::class, $imagen);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();


        if ($form->isSubmitted() && $form->isValid()) {
            $imagesFile = $form->get('nombre')->getData();

            if ($imagesFile) {
                $originalFilename = pathinfo($imagesFile->getClientOriginalName(), PATHINFO_FILENAME);
                $date = new \DateTime('now');
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '_' . $date->format('d-m-y') . '.' . $imagesFile->guessExtension();

                try {
                    $imagesFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Error al subir la imagen');
                }
                $imagen->setNombre($newFilename);
            }

            $this->aumentarCategorias($entityManager,$form);
            $entityManager->persist($imagen);
            $entityManager->flush();


            $this->addFlash('exito', 'Imagen guardada correctamente');

            $logger->info('La imagen :' . $form->get('nombre')->getData() . " guardado correctamente  el " . $date->format('d-m-y'));
            return $this->redirectToRoute('imagen_galeria');
        }

        $imagenes = $entityManager->getRepository(Imagenes::class)->findAllImages($entityManager);

        return $this->render('imagen_galeria/index.html.twig', [
            'controller_name' => 'subir imagen',
            'formulario' => $form->createView(),
            'imagenes' => $imagenes
        ]);
    }

    public function aumentarCategorias($entityManager, $form)
    {
        $categoria = $entityManager->getRepository(Categorias::class);
        $categoria=$categoria->find($form->get('categoria')->getData());
        $categoria->setNumImagenes($categoria->getNumImagenes() + 1);
    }


    /**
     * @Route("/imagen/individual/{id}", name="find_by_one")
     * @param $id
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function findOne($id, ManagerRegistry $doctrine): Response{

        $entityManager = $doctrine->getManager();

        $imagenNueva = $entityManager->getRepository(Imagenes::class)->find($id);
        if ($imagenNueva==null || !is_numeric($id)){
            $imagenNueva="vacio";
        }
        return $this->render('imagen_galeria/find_one_imagen.html.twig',[
            'imagen' => $imagenNueva,
            'id'=> $id
        ]);
    }

}
