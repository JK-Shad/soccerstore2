<?php

namespace App\Form;

use App\Entity\Assistance;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssistanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,[
                'label' => 'Votre nom',
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Votre prénom',
            ])
            ->add('email', TextType::class,[
                'label' => 'Votre email'
            ])
            ->add('contenu', TextEditorType::class,[
                'label' => 'Message'
            ])
            ->add('submit', SubmitType::class,[
                'label'=> 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary col-md-6 mt-5 mx-auto d-flex justify-content-center' 
                ]
            ])
               
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assistance::class,
        ]);
    }
}