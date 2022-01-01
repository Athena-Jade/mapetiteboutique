<?php

namespace App\Controller;


use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    } 

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
      
        return $this->render('home/index.html.twig', [
            'products' => $products,
            
        ]);
        
    }


    /**
     * @Route("/produits_arrivage", name="is_new_arrival")
     */
    public function arrival()
    { 
       $products = $this->entityManager->getRepository(Product::class)->findByIsNewArrival(1);
       

        return $this->render('home/product_arrival.html.twig', [
            'products' => $products,    
        ]);
    }

    /**
     * @Route("/produits_stars", name="is_featured")
     */
    public function featured()
    {    
        $products = $this->entityManager->getRepository(Product::class)->findByIsFeatured(1);
        
        
        return $this->render('home/product_featured.html.twig', [
            'products' => $products,    
        ]);
    }


    /**
     * @Route("/produits_promotions", name="is_special_offer")
     */
    public function offer()
    {
        
        $products = $this->entityManager->getRepository(Product::class)->findByIsSpecialOffer(1);
        
        
        return $this->render('home/product_specialoffer.html.twig', [
            'products' => $products,                                        
        ]);
    }
    
}

