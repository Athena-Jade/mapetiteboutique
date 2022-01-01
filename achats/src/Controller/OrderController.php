<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Classe\Cart; 
use App\Entity\Order; 
use App\Entity\OrderDetails; 
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; 



class OrderController extends AbstractController
{
    private $entityManager;    
    public function __construct(EntityManagerInterface $entityManager)  
    {
        $this->entityManager = $entityManager;
    }
    
    
    #[Route('/commande', name: 'order')]  // me redirige vers la page connexion de l'user
    public function index(Cart $cart, Request $request): Response
    {
        
          //dd ($this)->getUser()->getAddresses()->getValues;        //vérifier si l'user a une adresse de livraison ou pas. utiliser getValues pour récuperer une collection d'adresses (relation entre user et order)
            if (!$this->getUser()->getAddresses()->getValues()) {       //si user n' pas renseigné une adresse de livraison  
                return $this->redirectToRoute('account_address_add');   //alors redirection à page ajouter une adresse de livraison
            }
        
        
            $form=$this->createForm(OrderType::class, null, [ 
            'user'=>$this->getUser()   //récupération  de l'user qui est connecté
        ]);
        
        
        return $this->render('order/index.html.twig',[
            'form'=>$form->createView(),
            'cart'=>$cart->getFull()    //contenu du nouveau panier (c'est le recap de la commande ) 
        ]);
           
    }




    /**
     * @route("/commande/recapitulatif", name = "order_recap", methods = {"POST", "GET"})      //method post accepte uniquement les users qui viennent. Me redirige vers page connexion.    creation des commandes users en base de données
     */
      
    public function add(Cart $cart, Request $request): Response  //add permet de créer la commande en base de données
    {
        $form=$this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
            
        
        //formulaire pour finaliser valider la commande par l'user
       $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()) {
           // dd($form)->getData();
            
            $date = new \DateTime();
           
            $carriers = $form->get('carriers')->getData();
           // dd($carriers);
           
           $delivery = $form->get('addresses')->getData();   //je crée une chaîne de caractère avec $delivery
           // dd($delivery);
            
            $delivery_content = $delivery->getFirstName().''.$delivery->getLastName();  //je mets à l'intérieur de delivery_content (nom, prénom etc..)
            $delivery_content.='<br/>'.$delivery->getPhone();
            
            if ($delivery->getCompany()) {   //si l'user possède une société et a renseigné l'adresse
                $delivery_content .= '<br/>'.$delivery->getCompany();
            }    
                

            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().''.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();
            // dd($delivery_content);
           
            
           
            

            //enregistrer la commande de l'user avec Order()
            $order = new Order();


            $reference = $date->format('dmy').'-'.uniqid();   // permet de donner un n° à une commande afin d'avoir un suivi
            $order->setReference($reference);        

            $order->setUser($this->getUser()); // récuperation du bon user qui a passé la commande
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);  //commande non payé donc à zéro


            $this->entityManager->persist($order);    // 1) d'abord, je persite Order
     

           
            foreach($cart->getFull()as $product){  // pour chaque produit qui sont dans le panier, Symfony itère et fais une nouvelle entrée dans OrderDetails. Puis fais le lien entre OrderDetails et Order
               
                // dd($product);
               
                // enregistrer les produits avec OrderDetails()
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $this->entityManager->persist($orderDetails);     // 2) puis je persite OrderDetails

                //j'ai fini de construir mes objets je les passe à Doctrine pour les passer dans la bdd 
           
            }    
               
             //    dd($order);            

              $this->entityManager->flush();  // 3) je flush Order et OrderDetails
 
            return $this->render('order/add.html.twig',[
                
                'cart'=>$cart->getFull(),       //contenu du nouveau panier récap commande
                'carrier'=>$carriers,          // Affichage du prix de livraison du transporteur
                'delivery'=>$delivery_content, 
                'reference'=>$order ->getReference()
              
        
            ]);
        
        
        }    
           
        return $this->redirectToRoute('cart');
   
    }

}










