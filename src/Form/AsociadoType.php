<?php

namespace App\Form;

use App\Entity\Asociados;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AsociadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('logo', FileType::class,[
                'label'=>'Seleccione una imagen -->',
                'mapped'=>false,
                'required'=>true,
                'constraints'=> [
                    new File([
                        'maxSize'=>'1024k',
                        'mimeTypes'=>[
                            'image/jpeg',
                            'image/png',
                            'image/gift'
                        ],
                        'mimeTypesMessage'=>'Por favor, suba una imagen válida'
                    ])
                ]
            ])
            ->add('descripcion', TextareaType::class)
            ->add('Crear',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asociados::class,
        ]);
    }
}