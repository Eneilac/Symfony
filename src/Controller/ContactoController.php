<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Form\ContactoType;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContactoController extends AbstractController
{
    /**
     * @Route("/contacto", name="contacto")
     */
    public function index(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger, \Swift_Mailer $mailer,LoggerInterface $logger): Response
    {
        $contacto = new Mensajes();
        $form = $this->createForm(ContactoType::class, $contacto);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contacto);
            $entityManager->flush();

            $correo = (new \Swift_Message($form->get('asunto')->getData()))
                ->setFrom('eneilac02@informatica.iesvalledeljerteplasencia.es')
                ->setTo($form->get('email')->getData())
                ->setBody("Ha recibido un mensaje de: {$form->get('nombre')->getData()} con apellido: {$form->get('apellido')->getData()} para el correo: {$form->get('email')->getData()}el asunto del mensaje es: {$form->get('asunto')->getData()} y su contenido es: {$form->get('texto')->getData()}", 'text/plain');

            $mailer->send($correo);
            $this->addFlash('exito', 'Contacto guardado correctamente');
            $date = new \DateTime('now');
            $logger->info('El contacto :'.$form->get('nombre')->getData()." guardado correctamente  el ".$date->format('d-m-y'));
            return $this->redirectToRoute('contacto');
        }


        return $this->render('contacto/index.html.twig', [
            'controller_name' => 'subir contacto',
            'formulario' => $form->createView()
        ]);
    }
}
