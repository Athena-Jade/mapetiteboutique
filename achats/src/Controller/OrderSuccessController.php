<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    #[Route('/commande/merci/{stripeSessionid}', name: 'order_validate')]  
    public function index(Cart $cart,$stripeSessionid): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionid);  //recuperation de la commande avec la stripeSessionId 
     //   dd($order);

        
        
        //si la commande n'est pas trouvée ou l'utilisateur n' est pas le propriétaire de la commande alors redirect 'home'
        if(!$order || $order->getUser() !=$this->getUser()){
            return $this->redirectToRoute('home');
        }


        
        if (!$order->getIsPaid()) {   // 1er) si le statut est à 0
            
            // 3eme) vider la session cart de l'user dès qu'il a payé sa commande
            $cart->remove();  //vider le panier de l'user
            
            // 2eme) modifier le statut isPaid des commandes en mettant 1
            $order->setIsPaid(1);
           
           
            $this->entityManager->flush();
        }
        
    
        
        return $this->render('order_success/index.html.twig', [
            'controller_name' => 'OrderSuccessController',
            'order'=>$order
        ]);
    }
}
