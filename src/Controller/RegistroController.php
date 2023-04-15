<?php

namespace App\Controller;
use App\Entity\Usuarios;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    public function index(Request $request,ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= new Usuarios();
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);

            $plaintextPassword=$form["password"]->getData();

            $hashedPassword=$passwordHasher->hashPassword($user,$plaintextPassword);

            $user->setPassword($hashedPassword);

            $entityManager= $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('exito','Usuario registrado');
        }
        return  $this->render('registro/index.html.twig',[
            'controller_name'=>'RegistroController',
            'formulario'=>$form->createView()
        ]);
    }
}
