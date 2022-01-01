<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AccountPasswordController extends AbstractController
{
    
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    
    #[Route('/compte/modifier_mot_de_passe', name: 'account_password')]                      
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response   //UserPasswordEncoderInterface est une méthode pour comparer les 2 mots de passe 
    {
     //notification pour informer l'user que son nouveau mot de passe est ok ou pas
        $notification = null;


        $user = $this->getUser() ; // j'appelle l'user qui est connecté en ce moment
        $form = $this->createForm(ChangePasswordType::class, $user); 

        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()){         
            $old_pwd = $form->get('old_password')->getData();   //récupération data ancien mot de passe
            // dd($old_pwd);
            
            if ($encoder->isPasswordVAlid($user, $old_pwd)) {    // isPasswordValid vérifie que les 2 mots de passe sont identiques. 
                $new_pwd = $form->get('new_password')->getData(); // récupération nouveau mot de passe
             //   dd($new_pwd);


                $password = $encoder->encodePassword($user, $new_pwd); // encodage nouveau mot de passe
                $user->setPassword($password);   

               
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                
                $notification = "Votre mot de passe a bien été pris mis à jour";
            
            } else{
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
            
        }

        return $this->render('account/password.html.twig', [
            'controller_name' => 'AccountPasswordController',

            'form'=>$form->createView(),
            'notification' =>$notification
        ]);
    }

}
