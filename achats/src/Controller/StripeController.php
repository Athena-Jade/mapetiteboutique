<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")   / 
     */
    public function index(EntityManagerInterface $entityManager,Cart $cart, $reference)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference'=>$reference]);    //récupérer la commande avec sa reference
      
       
      ###################### NE PAS METTRE DE CONDITION IF CAR CELA BLOUE #################################"
        
        //si ne trouve pas order
      //  if(!$order){                                //ajout
     //       new JsonResponse(['error' => 'order']); //ajout
     //    }
      
    
     //  dd($order->getOrderDetails()->getValues());
        ##########################################################################
     
       // foreach($cart->getFull() as $product){
        foreach($order->getOrderDetails()->getValues() as $product){         // la commande                              
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());  
            
            $products_for_stripe[] = [
                'price_data' => [
        
                    'currency' => 'eur',
              
                    'unit_amount' =>$product->getPrice(),
              
                   'product_data' => [        //nom du produit
              
                      'name' => $product->getProduct(),
              
                      'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
              
                    ],
              
                ],
                'quantity' => $product ->getQuantity(),
            ];
        }

        
        //ajout transporteur.   Enregistrer le transporteur comme s'il s'agit d'un produit
        $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());   
            
            $products_for_stripe[] = [
                'price_data' => [
        
                    'currency' => 'eur',
              
                    'unit_amount' =>$order->getCarrierPrice(),
              
                   'product_data' => [ 
              
                      'name' => $order->getCarrierName(),
              
                      'images' => [$YOUR_DOMAIN],
              
                    ],
              
                ],
                'quantity' => 1,
            ];



        //utilisation de stripe, je mets ma clé api
        Stripe::setApiKey('sk_test_51IxBl1IHvIcXdkuKD6QWNg2cmH7SsnseF8w76qPrm4etOHJloZNdEAyJPmSFBugoVqGkGmZTS9hcAiqnCGUk75bG00NCB02t17');
            
            
        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
        
           'payment_method_types' => ['card'],
        
           'line_items' => [
               $products_for_stripe
           ],
        
           'mode' => 'payment',
        
           'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',    
        
           'cancel_url' => $YOUR_DOMAIN . '/commande/error/{CHECKOUT_SESSION_ID}',
        
        ]);

    
         
       //  dump($checkout_session->id);      // ne fonctionne pas!!!!!
        // dd($checkout_session);
       
        $order->setStripeSessionId($checkout_session->id);     
        $entityManager->flush();  

        $response = new JsonResponse(['id' => $checkout_session->id]); 
        return $response;
        
        
    }

   

}

