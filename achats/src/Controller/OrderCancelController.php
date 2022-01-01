<?php                               //dans le cas où l'user n'a pas assez d'argent dans son compte pour payer la commande 

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    
    
    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]  
    public function index($stripeSessionid): Response
    {
        
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionid);  // recuperation de la commande avec stripeSessionId 
        //   dd($order);
   
           
           
           //si la commande n'est pas trouvée alors redirect
           if(!$order || $order->getUser() !=$this->getUser()){
               return $this->redirectToRoute('home');
           }
        
        
           //envoyer email à l'user pour lui informer l'échec de paiement


        return $this->render('order_cancel/index.html.twig', [
            'controller_name' => 'OrderCancelController',
            'order'=>$order
        ]);
    }
}
