<?php

namespace App\Controller;

use App\Entity\Assistance;
use App\Form\AssistanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssistanceController extends AbstractController
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/assistance/help', name: 'assistance')]
    public function index(Request $request): Response
    {
        $notifier = null;
        $assistance = new Assistance();
        $form = $this->createForm(AssistanceType::class, $assistance);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $assistance = $form->getData();
            $this->em->persist($assistance);
            $this->em->flush();

            $notifier = 'Votre message à été envoyée';
        }
        return $this->render('assistance/index.html.twig', [
            'form' => $form->createView(),
            'notifier' =>$notifier,
        ]);
    }
}