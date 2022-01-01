<?php                        // creation class Cart pour le panier

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;


class Cart  
{
    
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
    
    public function add($id)    // création d'une fonction qui permet d'ajouter un produit dans le panier
    {
                    
        //pour stocker dans le panier le produit et la quantité
        $cart = $this->session->get('cart',[]);

        if (!empty($cart[$id])) {    //si dans le panier il y a déjà un produit insérer 
            $cart[$id]++;            //alors ajoute +1 quantité en plus
        }else {                     
            $cart[$id] =1;           //sinon, la quantité est 1 produit (qui est déjà dans le panier)
        }

        
       $this->session->set('cart', $cart);      //maintenant, je peux ajouter des prduits dans le panier qui vont s'additionner 
      
    }
    
   
    public function get()
    {
        return $this->session->get('cart');
    }
        
 
    
    public function remove()
    {
        return $this->session->remove('cart');
    }


    public function delete($id)
    {
        $cart = $this->session->get('cart',[]); 
       
        unset($cart[$id]);      //retirer du tableau cart l'entrée cart qui a l'id qui correspond 

        return $this->session->set('cart', $cart);

    }


    
    public function decrease($id)   // retirer un produit du panier
    {
        $cart = $this->session->get('cart',[]);    //vérifier si la quantité du produit n'est pas égale à 1 sinon ce n'est pas une dimunition de 1 Mais c'est supprimer le produit
        
        if ($cart[$id] > 1) {    // regarder dans mon panier si la quantité est supérieure à 1
           
            
            $cart[$id]--;         //si c'est supérieur à 1 alors retirer une quantité du panier
       
        } else {
            unset($cart[$id]);    // si ce n'est pas le cas alors supprimer le produit
        }   
       
        
        return $this->session->set('cart', $cart);
        
    }






    public function getFull()  //pour récupérer tous ce que contient le panier, utiliser getFull
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach($this->get() as $id => $quantity) {   //id= la clé,  quantity c'est la valeur
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                if (!$product_object) {  //Par mesure de sécurité, si tentative malveillante en tapant n'importe quoi  alors Symfony supprime le produit inexistant
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    } 








    

}






