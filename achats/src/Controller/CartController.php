<?php                                                                   // pour info: j'ai créé $cartComplete pour récupérer tous les données du produits afin d'afficher la vue dans Cart/index.html.twig

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Classe\Cart;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
   
   
    #[Route('/mon-panier', name:'cart')]       //OK AFFICHAGE RECAPITULATIF DU PANIER
    public function index(Cart $cart):Response
    {
  
       
        return $this->render('cart/index.html.twig', [
          'controller_name' => 'CartController',
          'cart'=>$cart->getFull()
        ]);
    
    
    }

 

    #[Route('/cart/add{id}', name: 'add_to_cart')]       //l'user ajoute des produits dans son panier
    public function add(Cart $cart, $id):Response       
    {
      //dd($id);
      $cart->add($id);
      
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]    //vider le panier
    public function remove(Cart $cart, ):Response
    {
      $cart->remove();
        
        return $this->redirectToRoute('products');
    }


    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]  // supprimer 1 quantité
    public function delete(Cart $cart, $id ):Response
    {
      $cart->delete($id);
        
        return $this->redirectToRoute('cart');
    }



    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]  //diminuer la quantité produit du panier
    public function decrease(Cart $cart, $id ):Response
    {
      $cart->decrease($id);
        
        return $this->redirectToRoute('cart');
    }





}
