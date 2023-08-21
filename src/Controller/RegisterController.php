<?php

namespace App\Controller;

use App\Classe\RegisterEmailService;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $em;
    private RegisterEmailService $emailService;


    public function __construct(EntityManagerInterface $em, RegisterEmailService $emailService)
    {
        $this->em = $em;
        $this->emailService = $emailService;
    }

    #[Route('/register', name: 'inscription')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, LoggerInterface $logger): Response
    {
        /*
           Création d'une nouvelle instance de la classe User 
           On stocke dans la propriété $user toutes les propriétés et méthodes de la classe User
        */
        $user = new User(); 
        
        /*
        On stocke dans la propriété $form, le RegisterType qui est liée à la propriété User
        $form va permettre de générer le formulaire 
        */
        $form = $this->createForm(RegisterType::class, $user);

        // permet de traiter la demande de soumission de formulaire en utilisant l'objet de formulaire $form et l'objet de requête HTTP $request.
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            /* Dans ce cas, $user = $form->getData(); récupère les données soumises dans le formulaire et les stocke dans la variable $user. 
            La méthode getData() renvoie les données soumises sous forme d'objet ou d'array, en fonction de la configuration du formulaire. */
            $user = $form->getData();


            $UserIsOk = $this->em->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            
            // On vérifie si l'utilisateur n'existe pas déjà dans la base de données
            if (!$UserIsOk) {
                // Hasher le mot de passe
                $hashedPassword = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);

                // Enregistrer l'utilisateur dans la base de données
                $this->em->persist($user);
                $this->em->flush();

                // Envoyer un email de confirmation à l'utilisateur
                $this->emailService->sendRegistrationConfirmationEmail($user);

                $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez dès à présent vous connecter.');

                // Une fois le compte créé on vide le formulaire
                return $this->redirectToRoute('inscription');
            } else {
                $this->addFlash('warning', 'L\'email renseignée existe déjà. Vous pouvez vous connecter.');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
