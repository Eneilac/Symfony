<?php

namespace App\Controller;

use App\Entity\Asociados;
use App\Form\AsociadoType;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AsociadoController extends AbstractController
{
    /**
     * @Route("/asociado", name="asociado")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    public function index(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, LoggerInterface $logger): Response
    {
        $asociado = new Asociados();
        $form = $this->createForm(AsociadoType::class, $asociado);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesFile = $form->get('logo')->getData();

            if ($imagesFile) {
                $originalFilename = pathinfo($imagesFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagesFile->guessExtension();

                try {
                    $imagesFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Error al subir la imagen');
                }
                $asociado->setLogo($newFilename);
            }
            $entityManager->persist($asociado);
            $entityManager->flush();

            $this->addFlash('exito', 'Asociado guardado correctamente');
            $date = new \DateTime('now');
            $logger->info('El Asociado :'.$form->get('nombre')->getData()." guardado correctamente  el ".$date->format('d-m-y'));
            return $this->redirectToRoute('asociado');
        }
        $asociados = $entityManager->getRepository(Asociados::class)->findAllAsociados($entityManager);
        return $this->render('asociado/index.html.twig', [
            'controller_name' => 'Crear Asociado',
            'asociados' => $asociados,
            'formulario' => $form->createView()
        ]);
    }
}
