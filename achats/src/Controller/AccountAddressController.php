<?php

namespace App\Controller;


use App\Entity\Address;
use App\Classe\Cart;

use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager; // pour récupérer les données de l'adresses de l'user, on a besoin de entityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    #[Route('/compte/adresses', name: 'account_address')]
    public function index(): Response
    {
       // dd($this)->getUser();
        
        return $this->render('account/address.html.twig');
            
    }



    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]  //permet à l'user d'ajouter une adresse
    public function add(Cart $cart, Request $request): Response
    {
       // dd($this)->getUser();

       $address = new Address();

       $form = $this->createForm(AddressType::class, $address);

       $form->handleRequest($request);
       if ($form->isSubmitted()&& $form->isValid()){
           $address->setUser($this->getUser());   //rattaché une adresse à un user
        

          //enregistrer les adresses de l'user
           $this->entityManager->persist($address);
           $this->entityManager->flush();
          // dd($address);
          
            if ($cart->get()) {    //si l'user a des produits dans son panier alors le rediriger vers order (pour passer ses commandes)
                return $this->redirectToRoute('order');
                
            } else {     //par contre si l'user n'a rien dans son panier alors redirection pour ajouter une adresse
                return $this->redirectToRoute('account_address'); 
            }
          
          
        }
        
        
        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
            
    }   





    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')]  //permet à l'user de modifier une adresse de livraison
    public function edit(Request $request, $id): Response
    {
       

       $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        if (!$address ||  $address->getUser() != $this->getUser()){ // es ce que l'adresse de livraison existe?  
            return $this->redirectToRoute('account_address');
        }



       $form = $this->createForm(AddressType::class, $address);

       $form->handleRequest($request);
       if ($form->isSubmitted()&& $form->isValid()){
        
           $this->entityManager->flush();

          return $this->redirectToRoute('account_address');
        }
        
        
        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
            
    }   

  

    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_address_delete')]  //supprimer une addresse
    public function delete($id): Response
    {
       

       $address = $this->entityManager->getRepository(Address::class)->findOneById($id);
        
       if ($address &&  $address->getUser() == $this->getUser()){ // es ce que l'adresse de livraison existe?  ou 
            $this->entityManager->remove($address);  //supprimer l'adresse
           
            $this->entityManager->flush();
        }



        return $this->redirectToRoute('account_address');
        
        
        
        
    }   



}
