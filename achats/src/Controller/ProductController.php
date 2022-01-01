<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $entityManager;   //pour récupérer tous mes produits, j'utilise entityManager
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    #[Route('/nos-produits', name: 'products')] 
    public function index(Request $request): Response  //  traitement du formulaire quand il est soumis avec Request $request
    {
    
        
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);  // OK AFFICHAGE DES PRODUITS avec le filtre
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){  
            // $search = $form->getData();
            // dd ($search);
           $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search); // creation findWithSearch dans ProductRepository. 
        }else{
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }
             
           
            //si le produit n'existe pas! Alors redirection à la page produits
            if(!$products){
                return $this->redirectToRoute('products');
                
            }
                                                                                        
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,  
            'form' =>$form->createView(),  
            
        ]);
    }

    
    
    #[Route('/produit/{slug}', name: 'product')]   // OK AFFICHAGE D'UN ARTICLE avec son slug
    public function show($slug): Response
    {
       
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['slug'=>$slug]);  
      //  dd($slug);
        
       $products = $this->entityManager->getRepository(Product::class)->findByisBest(1);

       //si le produit n'existe pas ! Alors redirection à la page produits
       if(!$product){
           return $this->redirectToRoute('products');
       }

        return $this->render('product/show.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,   //ne pas mettre au pluriel sinon ça bloque
            'products'=>$products
        ]);
    }


  
    
    
}
