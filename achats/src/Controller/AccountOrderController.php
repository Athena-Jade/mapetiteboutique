<?php

namespace App\Controller;             // ce fichier permet de voir les commandes passées (archives) de l'user

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    
    
    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());   //findSuccessOrder n'existe pas, je l'ai créé dans OrderRepository

        
       //  dd($orders);
        
        
        return $this->render('account/order.html.twig', [
          'orders'=>$orders
        ]);
    }




    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]  //commande détaillée
    public function show($reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);  

       //  dd($orders);
        
        //si order n'existe pas ou que l'order n'appartient pas à l'user en cours
        if (!$order || $order->getUser() !=$this->getUser()) {
            return $this->redirectToRoute('account_order'); // alors redirecto compte account_order
        }


        return $this->render('account/order_show.html.twig', [
       
          'order'=>$order
        ]);
    }











}
