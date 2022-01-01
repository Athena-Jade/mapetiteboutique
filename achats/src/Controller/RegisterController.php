<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
//use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
        

    #[Route('/inscription', name: 'register')]    // OK AFFICHAGE
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
       //création d'une notification (pour informer l'user que son inscription est bien enregistrée)
       $notification = null;
       
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);  // traitement du formulaire

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

           
            // je m'assure que l'user n'est pas déjà enregistré en bdd
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
           
            if (!$search_email) { // si le mail n'est pas enregistré en base de données alors inscription
           
               
                //encodage du mot de passe
                $password = $encoder->encodePassword($user, $user->getPassword());  //stocker le mot de passe de l'user grâce à getPassword

                $user->setPassword($password); // setPassword permet de remplir la variable avec une data

                 // dd($password);
                 //  dd($user);
                  
                
                  $this->entityManager->persist($user); // fige la data
                   
               
                  $this->entityManager->flush();  // enregistrement en bd
                  
                

                $notification = "Votre inscription a bien été enregistré, connectez-vous à présent sur votre compte";


            } else { //sinon envoie notification à l'user pour lui informer de choisir un autre email
               
                 // je vais envoyer un message de notification à l'user qui vient de s'incrire
                $notification = "L'email que vous avez renseigné, existe déjà. Veuiller en choisir une autre";
            

            }          
        
        }
        

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form'=>$form->createView(),
            'notification' => $notification,
           
        ]);
    
    }

}
